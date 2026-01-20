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

    public $nik;

    public $tempat_lahir_nama;

    public $tgl_lahir;

    public $jenis_kelamin;

    public $nomor_hp;

    public $email;

    public $alamat;

    public function setPegawai($pegawai)
    {

        $this->fill($pegawai->toArray());
    }
}
