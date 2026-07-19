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
        'nama_bendahara',
        'status',
    ];

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
