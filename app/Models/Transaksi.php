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
    'file_ba_manual', 'file_buku_kas', 'file_buku_pembantu_bank', 'file_rekening_koran'
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
}
