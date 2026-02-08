<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'pegawai_id',
        'tanggal',
        'status',
        'keterangan',
        'created_by',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }
}
