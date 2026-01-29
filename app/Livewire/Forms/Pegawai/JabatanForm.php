<?php

namespace App\Livewire\Forms\Pegawai;

use Livewire\Attributes\Validate;
use Livewire\Form;

class JabatanForm extends Form
{
    #[Validate('nullable')]
    public ?string $gol_awal_nama = null;

    #[Validate('nullable')]
    public ?string $gol_nama = null;

    #[Validate('nullable')]
    public ?string $tmt_golongan = null;

    #[Validate('nullable')]
    public ?string $mkgol = null;

    #[Validate('nullable')]
    public ?string $jenis_jabatan_nama = null;

    #[Validate('nullable')]
    public ?string $jabatan_nama = null;

    #[Validate('nullable')]
    public ?string $tmt_jabatan = null;

    #[Validate('nullable')]
    public ?string $jabatan_non_definitif = null;

    #[Validate('nullable')]
    public ?string $jabatan_non_definitif_1 = null;

    #[Validate('nullable')]
    public ?string $mkjab = null;

    #[Validate('nullable')]
    public ?string $kelas_jabatan = null;

    #[Validate('nullable')]
    public ?string $kelompok_jabatan = null;

    #[Validate('nullable')]
    public ?string $pangkat = null;

    #[Validate('nullable')]
    public ?string $proyeksi_jf = null;

    #[Validate('nullable')]
    public ?string $keterangan_status = null;

    public function setPegawai($pegawai)
    {
        $this->fill($pegawai->toArray());
    }
}
