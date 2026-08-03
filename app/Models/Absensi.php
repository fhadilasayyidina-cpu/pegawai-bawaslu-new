<?php

namespace App\Models;

use App\Enums\JenisAbsen;
use App\Enums\StatusAbsensi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'pegawai_id',
        'nip',
        'tanggal',
        'scan_masuk',
        'scan_pulang',
        'status',
        'jenis_absen',
        'keterangan',
        'created_by',
    ];

    protected $attributes = [
        'status' => 'Hadir',
        'jenis_absen' => 'WFO',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'status' => StatusAbsensi::class,
            'jenis_absen' => JenisAbsen::class,
        ];
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }
}
