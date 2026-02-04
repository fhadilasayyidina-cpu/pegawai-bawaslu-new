<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuti extends Model
{
    use HasFactory;

    protected $fillable = [
        'pegawai_id',
        'nomor_surat',
        'jenis_cuti',
        'alasan',
        'tanggal_mulai',
        'tanggal_selesai',
        'lama_hari',
        'keterangan',
        'nama_kepala_sekretariat',
        'nip_kepala_sekretariat',
        'nama_sekjen',
        'nip_sekjen',
        'nomor_surat_edaran',
        // Cuti Sakit
        'status_dokter',
        'nama_dokter',
        'nomor_surat_dokter',
        // Cuti Melahirkan
        'jenis_melahirkan',
        'tanggal_perkiraan_lahir',
        // Cuti Luar Tanggungan
        'tanpa_gaji',
        'alasan_luar_tanggungan',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'tanggal_perkiraan_lahir' => 'date',
        'tanpa_gaji' => 'boolean',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
}
