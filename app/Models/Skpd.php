<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Skpd extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll()->logOnlyDirty();
    }

    protected $fillable = [
        'kode',
        'nama',
        'no_whatsapp',
        'nama_bendahara',
        'status',
    ];

    public function getWhatsappUrl($message = '')
    {
        if (!$this->no_whatsapp) {
            return '#';
        }

        // Format nomor dari 08... ke 628...
        $number = $this->no_whatsapp;
        $number = preg_replace('/[^0-9]/', '', $number); // buang karakter non-angka
        
        if (str_starts_with($number, '08')) {
            $number = '628' . substr($number, 2);
        }

        return 'https://wa.me/' . $number . '?text=' . urlencode($message);
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }

    public function pengaturan()
    {
        return $this->hasOne(Pengaturan::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
