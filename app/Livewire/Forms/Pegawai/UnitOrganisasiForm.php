<?php

namespace App\Livewire\Forms\Pegawai;

use Livewire\Attributes\Validate;
use Livewire\Form;

class UnitOrganisasiForm extends Form
{
    #[Validate('nullable')]
    public ?int $pegawai_id = null;

    #[Validate('nullable')]
    public ?string $satuan_kerja = '';

    #[Validate('nullable')]
    public ?string $unit_kerja = '';

    #[Validate('nullable')]
    public ?string $unit_organisasi = '';

    #[Validate('nullable')]
    public ?string $unor_nama = '';

    #[Validate('nullable')]
    public ?string $instansi_induk_nama = '';

    #[Validate('nullable')]
    public ?string $eselon = '';

    #[Validate('nullable')]
    public ?string $divisi = '';

    #[Validate('nullable')]
    public ?string $ukm = '';

    #[Validate('nullable')]
    public ?string $provinsi = '';

    #[Validate('nullable')]
    public ?string $kab_kota = '';

    #[Validate('nullable')]
    public ?string $jenis_pegawai = '';

    #[Validate('nullable')]
    public ?string $status_kepegwaian = '';

    public function setPegawai(\App\Models\Pegawai $pegawai): void
    {
        $this->pegawai_id = $pegawai->id;

        $this->satuan_kerja = $pegawai->satuan_kerja ?? '';
        $this->unit_kerja = $pegawai->unit_kerja ?? '';
        $this->unit_organisasi = $pegawai->unit_organisasi ?? '';
        $this->unor_nama = $pegawai->unor_nama ?? '';
        $this->instansi_induk_nama = $pegawai->instansi_induk_nama ?? '';
        $this->eselon = $pegawai->eselon ?? '';
        $this->divisi = $pegawai->divisi ?? '';
        $this->ukm = $pegawai->ukm ?? '';
        $this->provinsi = $pegawai->provinsi ?? '';
        $this->kab_kota = $pegawai->kab_kota ?? '';
        $this->jenis_pegawai = $pegawai->jenis_pegawai ?? '';
        $this->status_kepegwaian = $pegawai->status_kepegwaian ?? '';
    }
}
