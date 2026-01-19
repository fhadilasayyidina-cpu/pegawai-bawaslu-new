<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $fillable = [
        'nip_baru',
        'nip_lama',
        'nama',
        'gelar_depan',
        'gelar_blk',
        'keterangan',
        'tempat_lahir_nama',
        'tgl_lahir',
        'jenis_kelamin',
        'agama_nama',
        'jenis_kawin_nama',
        'nik',
        'nomor_hp',
        'email',
        'email_gov',
        'alamat',
        'npwp_nomor',
        'bpjs',
        'kartu_pegawai',
        'nomor_sk_cpns',
        'tgl_sk_cpns',
        'tmt_cpns',
        'nomor_sk_pns',
        'tgl_sk_pns',
        'tmt_pns',
        'gol_awal_nama',
        'gol_nama',
        'tmt_golongan',
        'jenis_jabatan_nama',
        'jabatan_nama',
        'tmt_jabatan',
        'unor_nama',
        'instansi_induk_nama',
        'eselon',
        'kelompok_jabatan',
        'nm_kelompok_jabatan',
        'range_umur',
        'kelas_jabatan',
        'keterangan_status',
        'tingkat_pendidikan_nama',
        'satuan_kerja',
        'provinsi',
        'kab_kota',
        'jenis_pegawai',
        'status_kepegwaian',
    ];

    protected function casts(): array
    {
        return [
            'tgl_lahir' => 'date',
            'tgl_sk_cpns' => 'date',
            'tmt_cpns' => 'date',
            'tgl_sk_pns' => 'date',
            'tmt_pns' => 'date',
            'tmt_golongan' => 'date',
            'tmt_jabatan' => 'date',
        ];
    }
}
