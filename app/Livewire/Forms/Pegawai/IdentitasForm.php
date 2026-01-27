<?php

namespace App\Livewire\Forms\Pegawai;

use Livewire\Attributes\Validate;
use Livewire\Form;

class IdentitasForm extends Form
{
    #[Validate('required')]
    public $nama = '';

    #[Validate('required')]
    public $nip_baru = '';

    public $nip_lama;

    public $nik;

    public $tempat_lahir_nama;

    public $tgl_lahir;

    public $jenis_kelamin;

    public $nomor_hp;

    public $email;

    public $alamat;

    public $gelar_depan;

    public $gelar_blk;

    public $gol_darah;

    public $agama_nama;

    public $jenis_kawin_nama;

    public $email_gov;

    public $foto;

    public $usia;

    public $range_umur;

    public function setPegawai($pegawai)
    {

        $this->fill($pegawai->toArray());
    }
}
