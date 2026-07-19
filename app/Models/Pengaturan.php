<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Pengaturan extends Model
{
    use HasFactory, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    protected $fillable = [
        'skpd_id',
        'logo',
        'isi_kop',
        'nama_kepala', 'nip_kepala', 'pangkat_kepala', 'jabatan_kepala',
        'nama_bendahara', 'nip_bendahara', 'pangkat_bendahara', 'jabatan_bendahara',
        'nama_kasubag', 'nip_kasubag', 'pangkat_kasubag', 'jabatan_kasubag',
    ];

    public function skpd()
    {
        return $this->belongsTo(Skpd::class);
    }
}
