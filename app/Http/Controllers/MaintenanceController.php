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

    public function backup()
    {
        try {
            $driver = DB::connection()->getDriverName();

            // Khusus untuk SQLite, cukup salin dan download filenya
            if ($driver === 'sqlite') {
                $dbPath = DB::connection()->getDatabaseName();
                if (file_exists($dbPath)) {
                    $fileName = 'backup_sqlite_' . date('Y_m_d_His') . '.sqlite';
                    $copyPath = storage_path('app/' . $fileName);
                    copy($dbPath, $copyPath);
                    return response()->download($copyPath)->deleteFileAfterSend(true);
                }
                throw new \Exception("File SQLite tidak ditemukan.");
            }

            // Untuk MySQL / MariaDB
            $dbName = env('DB_DATABASE');
            $tables = DB::select('SHOW TABLES');
            $property = 'Tables_in_' . $dbName;
            
            $sqlScript = "-- SiReKe Database Backup\n";
            $sqlScript .= "-- Generated: " . date('Y-m-d H:i:s') . "\n\n";
            
            foreach ($tables as $table) {
                $tableName = $table->$property;
                
                // Add Drop Table if exists
                $sqlScript .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
                
                // Get create table structure
                $createTable = DB::select("SHOW CREATE TABLE {$tableName}");
                $sqlScript .= $createTable[0]->{'Create Table'} . ";\n\n";
                
                // Get rows
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

            $fileName = 'backup_' . $dbName . '_' . date('Y_m_d_His') . '.sql';
            $filePath = storage_path('app/' . $fileName);
            
            file_put_contents($filePath, $sqlScript);
            
            return response()->download($filePath)->deleteFileAfterSend(true);
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
