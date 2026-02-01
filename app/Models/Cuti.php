<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cuti extends Model
{
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
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
}
