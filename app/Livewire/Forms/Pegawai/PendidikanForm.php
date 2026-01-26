<?php

namespace App\Livewire\Forms\Pegawai;

use Livewire\Form;

class PendidikanForm extends Form
{
    public ?int $pegawai_id = null;

    public ?string $pendidikan_tertinggi_nama = '';

    public ?string $jurusan = '';

    public ?string $nama_sekolah = '';

    public ?string $nomor_ijazah = '';

    public ?string $tahun_lulus = '';

    public function setPegawai(\App\Models\Pegawai $pegawai): void
    {
        $this->pegawai_id = $pegawai->id;

        $this->pendidikan_tertinggi_nama = $pegawai->pendidikan_tertinggi_nama ?? '';
        $this->jurusan = $pegawai->jurusan ?? '';
        $this->nama_sekolah = $pegawai->nama_sekolah ?? '';
        $this->nomor_ijazah = $pegawai->nomor_ijazah ?? '';
        $this->tahun_lulus = $pegawai->tahun_lulus ?? '';
    }
}
