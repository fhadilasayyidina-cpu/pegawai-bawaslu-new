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

    #[Validate('nullable')]
    public $nip_lama;

    #[Validate('nullable')]
    public $nik;

    #[Validate('nullable')]
    public $tempat_lahir_nama;

    #[Validate('nullable')]
    public $tgl_lahir;

    #[Validate('nullable')]
    public $jenis_kelamin;

    #[Validate('nullable')]
    public $nomor_hp;

    #[Validate('nullable')]
    public $email;

    #[Validate('nullable')]
    public $alamat;

    #[Validate('nullable')]
    public $gelar_depan;

    #[Validate('nullable')]
    public $gelar_blk;

    #[Validate('nullable')]
    public $gol_darah;

    #[Validate('nullable')]
    public $agama_nama;

    #[Validate('nullable')]
    public $jenis_kawin_nama;

    #[Validate('nullable')]
    public $email_gov;

    #[Validate('nullable')]
    public $foto;

    #[Validate('nullable')]
    public $usia;

    #[Validate('nullable')]
    public $range_umur;

    public function setPegawai($pegawai)
    {
        $data = $pegawai->toArray();

        // Format date fields as Y-m-d for HTML5 date inputs
        if (isset($data['tgl_lahir']) && $data['tgl_lahir'] !== null) {
            $data['tgl_lahir'] = $pegawai->tgl_lahir?->format('Y-m-d');
        }

        $this->fill($data);
    }
}
