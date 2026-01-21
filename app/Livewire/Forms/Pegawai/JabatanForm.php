<?php

namespace App\Livewire\Forms\Pegawai;

use Livewire\Form;

class JabatanForm extends Form
{
    public $gol_awal_nama;

    public $gol_nama;

    public $tmt_golongan;

    public $mkgol;

    public $jenis_jabatan_nama;

    public $jabatan_nama;

    public $tmt_jabatan;

    public $jabatan_non_definitif;

    public $jabatan_non_definitif_1;

    public $mkjab;

    public $kelas_jabatan;

    public $kelompok_jabatan;

    public $pangkat;

    public $proyeksi_jf;

    public $keterangan_status;

    public function setPegawai($pegawai)
    {
        $this->fill($pegawai->toArray());
    }
}
