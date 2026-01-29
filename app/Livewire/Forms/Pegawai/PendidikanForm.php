<?php

namespace App\Livewire\Forms\Pegawai;

use Livewire\Attributes\Validate;
use Livewire\Form;

class PendidikanForm extends Form
{
    #[Validate('nullable')]
    public ?int $pegawai_id = null;

    #[Validate('nullable')]
    public ?string $tingkat_pendidikan_nama = '';

    #[Validate('nullable')]
    public ?string $pendidikan_nama = '';

    #[Validate('nullable')]
    public ?string $pendidikan_tertinggi_nama = '';

    #[Validate('nullable')]
    public ?string $jurusan = '';

    #[Validate('nullable')]
    public ?string $nama_sekolah = '';

    #[Validate('nullable')]
    public ?string $nomor_ijazah = '';

    #[Validate('nullable')]
    public ?string $tahun_lulus = '';

    #[Validate('nullable')]
    public ?string $riwayat_diklatpim = '';

    public function setPegawai(\App\Models\Pegawai $pegawai): void
    {
        $this->pegawai_id = $pegawai->id;

        $this->tingkat_pendidikan_nama = $pegawai->tingkat_pendidikan_nama ?? '';
        $this->pendidikan_nama = $pegawai->pendidikan_nama ?? '';
        $this->pendidikan_tertinggi_nama = $pegawai->pendidikan_tertinggi_nama ?? '';
        $this->jurusan = $pegawai->jurusan ?? '';
        $this->nama_sekolah = $pegawai->nama_sekolah ?? '';
        $this->nomor_ijazah = $pegawai->nomor_ijazah ?? '';
        $this->tahun_lulus = $pegawai->tahun_lulus ?? '';
        $this->riwayat_diklatpim = $pegawai->riwayat_diklatpim ?? '';
    }
}
