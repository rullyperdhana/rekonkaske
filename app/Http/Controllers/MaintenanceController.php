<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MaintenanceController extends Controller
{
    public function index()
    {
        return view('pengaturan.maintenance.index');
    }

    public function backup(Request $request)
    {
        try {
            $driver = DB::connection()->getDriverName();
            $dbName = env('DB_DATABASE', 'database');
            
            $dbFileName = '';
            $dbFilePath = '';

            // 1. Siapkan file database terlebih dahulu
            if ($driver === 'sqlite') {
                $dbPath = DB::connection()->getDatabaseName();
                if (file_exists($dbPath)) {
                    $dbFileName = 'backup_sqlite_' . date('Y_m_d_His') . '.sqlite';
                    $dbFilePath = storage_path('app/' . $dbFileName);
                    copy($dbPath, $dbFilePath);
                } else {
                    throw new \Exception("File SQLite tidak ditemukan.");
                }
            } else {
                $tables = DB::select('SHOW TABLES');
                $property = 'Tables_in_' . $dbName;
                
                $sqlScript = "-- SiReKe Database Backup\n";
                $sqlScript .= "-- Generated: " . date('Y-m-d H:i:s') . "\n\n";
                
                foreach ($tables as $table) {
                    $tableName = $table->$property;
                    $sqlScript .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
                    
                    $createTable = DB::select("SHOW CREATE TABLE {$tableName}");
                    $sqlScript .= $createTable[0]->{'Create Table'} . ";\n\n";
                    
                    $rows = DB::table($tableName)->get();
                    foreach ($rows as $row) {
                        $rowArray = (array) $row;
                        $values = array_map(function ($value) {
                            if (is_null($value)) return "NULL";
                            $value = addslashes($value);
                            $value = str_replace("\n", "\\n", $value);
                            $value = str_replace("\r", "\\r", $value);
                            return "'" . $value . "'";
                        }, array_values($rowArray));
                        
                        $sqlScript .= "INSERT INTO `{$tableName}` (`" . implode("`, `", array_keys($rowArray)) . "`) VALUES (" . implode(", ", $values) . ");\n";
                    }
                    $sqlScript .= "\n";
                }

                $dbFileName = 'backup_' . $dbName . '_' . date('Y_m_d_His') . '.sql';
                $dbFilePath = storage_path('app/' . $dbFileName);
                file_put_contents($dbFilePath, $sqlScript);
            }

            // 2. Cek apakah dokumen pendukung disertakan
            if ($request->has('include_dokumen') && $request->include_dokumen == 1) {
                $zipFileName = 'backup_full_sireke_' . date('Y_m_d_His') . '.zip';
                $zipFilePath = storage_path('app/' . $zipFileName);

                $zip = new \ZipArchive();
                if ($zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === TRUE) {
                    // Masukkan file DB ke dalam zip
                    $zip->addFile($dbFilePath, $dbFileName);

                    // Masukkan semua file dokumen dari storage/app/public/dokumen_rekonsiliasi
                    $dokumenPath = storage_path('app/public/dokumen_rekonsiliasi');
                    if (is_dir($dokumenPath)) {
                        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dokumenPath), \RecursiveIteratorIterator::LEAVES_ONLY);
                        foreach ($files as $name => $file) {
                            if (!$file->isDir()) {
                                $filePath = $file->getRealPath();
                                $relativePath = 'dokumen_rekonsiliasi/' . substr($filePath, strlen($dokumenPath) + 1);
                                $zip->addFile($filePath, $relativePath);
                            }
                        }
                    }

                    $zip->close();
                    
                    // Hapus file DB temporary karena sudah masuk ke zip
                    if (file_exists($dbFilePath)) {
                        unlink($dbFilePath);
                    }

                    return response()->download($zipFilePath)->deleteFileAfterSend(true);
                } else {
                    throw new \Exception("Gagal membuat file ZIP.");
                }
            }

            // Jika tidak mencentang dokumen, kirim file DB saja
            return response()->download($dbFilePath)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error('Backup DB Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal membuat backup database: ' . $e->getMessage());
        }
    }

    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file',
        ]);

        try {
            $file = $request->file('backup_file');
            $driver = DB::connection()->getDriverName();

            if ($driver === 'sqlite') {
                // Pastikan file yang diupload adalah sqlite
                if ($file->getClientOriginalExtension() !== 'sqlite') {
                    return back()->with('error', 'File backup harus berakhiran .sqlite untuk database SQLite.');
                }
                
                $dbPath = DB::connection()->getDatabaseName();
                // Hapus koneksi sementara agar tidak ada file lock
                DB::disconnect();
                // Replace file
                copy($file->getRealPath(), $dbPath);
                
                return back()->with('success', 'Database SQLite berhasil direstore (dipulihkan).');
            } else {
                // Untuk MySQL, file yang diupload adalah .sql
                if ($file->getClientOriginalExtension() !== 'sql') {
                    return back()->with('error', 'File backup harus berakhiran .sql untuk database MySQL.');
                }

                $sql = file_get_contents($file->getRealPath());
                DB::unprepared($sql);

                return back()->with('success', 'Database MySQL berhasil direstore (dipulihkan).');
            }
        } catch (\Exception $e) {
            Log::error('Restore DB Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal melakukan restore database: ' . $e->getMessage());
        }
    }

    public function reset(Request $request)
    {
        $request->validate([
            'password' => 'required',
            'tipe_data' => 'required|array',
            'tipe_data.*' => 'in:transaksi,log'
        ]);

        if (!Hash::check($request->password, auth()->user()->password)) {
            return back()->with('error', 'Kata Sandi (Password) yang Anda masukkan salah. Penghapusan dibatalkan demi keamanan.');
        }

        $tipeData = $request->tipe_data;
        $pesan = [];

        try {
            // Nonaktifkan pemeriksaan foreign key sementara
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            if (in_array('transaksi', $tipeData)) {
                // Hapus data transaksi (soft deletes atau hard delete)
                DB::table('transaksis')->truncate();
                $pesan[] = 'Data Transaksi & Berita Acara';
            }

            if (in_array('log', $tipeData)) {
                // Hapus log aktivitas
                DB::table('activity_log')->truncate();
                $pesan[] = 'Riwayat Log Aktivitas';
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            // Tambahkan log admin telah mereset data
            activity('maintenance')
                ->causedBy(auth()->user())
                ->log('Admin MENGHAPUS PERMANEN data: ' . implode(', ', $pesan));

            return back()->with('success', 'Berhasil mereset data: ' . implode(', ', $pesan) . ' secara permanen.');
        } catch (\Exception $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            Log::error('Reset Data Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }
}
