<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

#[Fillable([
    'skpd_id', 'rekening_id', 'periode_bulan', 'periode_tahun',
    'bku_saldo_awal', 'bku_penerimaan', 'bku_pengeluaran', 'bku_saldo_akhir',
    'bank_saldo_awal', 'bank_penerimaan', 'bank_pengeluaran', 'bank_saldo_akhir',
    'keterangan_selisih', 'tanggal_ba', 'status_verifikasi', 'file_bukti', 'user_id',
    'file_ba_manual', 'file_buku_kas', 'file_buku_pembantu_bank', 'file_rekening_koran',
    'snapshot_pengantar_ba', 'snapshot_penutup_ba',
    'status_konsolidator', 'catatan_konsolidator_terakhir', 'checked_by', 'checked_at'
])]
class Transaksi extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    public function skpd()
    {
        return $this->belongsTo(Skpd::class);
    }

    public function rekening()
    {
        return $this->belongsTo(Rekening::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function checker()
    {
        return $this->belongsTo(User::class, 'checked_by');
    }

    public function catatans()
    {
        return $this->hasMany(TransaksiCatatan::class)->orderBy('created_at', 'desc');
    }

    /**
     * Hitung batas bulan maksimum yang diizinkan untuk dilaporkan oleh Operator SKPD.
     * Aturan Bisnis Pelaporan Kas & Rekon:
     * - Tahun lampau (< tahun berjalan): Semua bulan (1..12) diizinkan.
     * - Tahun mendatang (> tahun berjalan): Belum ada bulan yang diizinkan (0).
     * - Tahun berjalan (== tahun berjalan):
     *   * Bulan 1 s/d 11: Hanya bulan pelaporan yang sudah selesai yang aktif (currMonth - 1).
     *     Contoh: Jika saat ini bulan November (11), maka yang aktif adalah pelaporan bulan Oktober (10)
     *     dan bulan-bulan di bawah Oktober yang belum ada laporan. Bulan November baru aktif apabila login di bulan Desember (12).
     *     Untuk bulan Januari (1), belum ada bulan yang selesai pada tahun berjalan (0).
     *   * Bulan 12 (Desember):
     *     - Tanggal 1 s/d 20: Hanya sampai bulan November (11) yang aktif.
     *     - Di atas tanggal 20 (> 20 Desember): Bulan Desember (12) baru dibuka dan aktif untuk penutupan kas akhir tahun.
     */
    public static function getMaxAllowedMonth(?int $tahun = null, ?\DateTimeInterface $now = null): int
    {
        $now = $now ?? now()->setTimezone('Asia/Makassar');
        $currYear = (int)$now->format('Y');
        $currMonth = (int)$now->format('n');
        $currDay = (int)$now->format('j');

        $tahun = $tahun ?? (session('tahun_login') ? (int)session('tahun_login') : $currYear);

        if ($tahun < $currYear) {
            return 12;
        }

        if ($tahun > $currYear) {
            return 0;
        }

        if ($currMonth < 12) {
            return max(0, $currMonth - 1);
        }

        // Bulan Desember: Hanya diizinkan jika sudah di atas tanggal 20
        return ($currDay > 20) ? 12 : 11;
    }

    /**
     * Cek apakah bulan tertentu diizinkan untuk dipilih.
     */
    public static function isMonthAllowed(int $bulan, ?int $tahun = null, ?\DateTimeInterface $now = null): bool
    {
        if ($bulan < 1 || $bulan > 12) {
            return false;
        }

        $max = self::getMaxAllowedMonth($tahun, $now);
        return $bulan <= $max;
    }

    /**
     * Dapatkan nama bulan Indonesia.
     */
    public static function getNamaBulan(int $bulan): string
    {
        $namaBulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        return $namaBulan[$bulan - 1] ?? 'Bulan ' . $bulan;
    }
}


