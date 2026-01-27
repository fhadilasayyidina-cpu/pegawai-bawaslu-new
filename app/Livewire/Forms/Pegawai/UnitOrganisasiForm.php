<?php

namespace App\Livewire\Forms\Pegawai;

use Livewire\Form;

class UnitOrganisasiForm extends Form
{
    public ?int $pegawai_id = null;

    public ?string $satuan_kerja = '';

    public ?string $unit_kerja = '';

    public ?string $unit_organisasi = '';

    public ?string $unor_nama = '';

    public ?string $instansi_induk_nama = '';

    public ?string $eselon = '';

    public ?string $divisi = '';

    public ?string $ukm = '';

    public ?string $provinsi = '';

    public ?string $kab_kota = '';

    public ?string $jenis_pegawai = '';

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
