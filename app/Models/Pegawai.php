<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawais';

    protected $fillable = [
        // Identitas
        'nip_baru',
        'nip_lama',
        'nama',
        'gelar_depan',
        'gelar_blk',
        'tempat_lahir_nama',
        'jenis_kelamin',
        'gol_darah',
        'agama_nama',
        'jenis_kawin_nama',
        'nik',
        'nomor_hp',
        'email',
        'email_gov',
        'alamat',
        'tgl_lahir',
        'usia',

        // Administrasi
        'npwp_nomor',
        'bpjs',
        'kartu_pegawai',
        'nomor_sk_cpns',
        'tgl_sk_cpns',
        'tmt_cpns',
        'nomor_sk_pns',
        'tgl_sk_pns',
        'tmt_pns',
        'no_sk_dpk_penugasan_kontrak',
        'tgl_sk_dpk_penugasan_kontrak',
        'keterangan',
        'keterangan_status',

        // Golongan & Jabatan
        'gol_awal_nama',
        'gol_nama',
        'tmt_golongan',
        'mkgol',
        'jenis_jabatan_nama',
        'jabatan_nama',
        'tmt_jabatan',
        'jabatan_non_definitif',
        'jabatan_non_definitif_1',
        'mkjab',
        'jumlah',
        'kelas',
        'kelas_jabatan',
        'kelompok_jabatan',
        'nm_kelompok_jabatan',
        'nama_kelompok_jabatan',
        'pangkat',
        'proyeksi_jf',

        // Pendidikan
        'tingkat_pendidikan_nama',
        'pendidikan_nama',
        'tahun_lulus',
        'riwayat_diklatpim',

        // Unit & Organisasi
        'satuan_kerja',
        'unit_kerja',
        'unit_organisasi',
        'unor_nama',
        'instansi_induk_nama',
        'eselon',
        'divisi',
        'ukm',
        'range_umur',

        // Lokasi
        'provinsi',
        'kab_kota',

        // Status Pegawai
        'jenis_pegawai',
        'status_kepegwaian',
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
        'tgl_sk_cpns' => 'date',
        'tmt_cpns' => 'date',
        'tgl_sk_pns' => 'date',
        'tmt_pns' => 'date',
        'tgl_sk_dpk_penugasan_kontrak' => 'date',
        'tmt_golongan' => 'date',
        'tmt_jabatan' => 'date',
    ];
}
