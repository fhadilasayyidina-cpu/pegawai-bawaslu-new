<?php

namespace App\Livewire\Forms\Pegawai;

use Livewire\Attributes\Validate;
use Livewire\Form;

class AdministrasiForm extends Form
{
    #[Validate('nullable')]
    public ?string $npwp_nomor = null;

    #[Validate('nullable')]
    public ?string $bpjs = null;

    #[Validate('nullable')]
    public ?string $kartu_pegawai = null;

    #[Validate('nullable')]
    public ?string $nomor_sk_cpns = null;

    #[Validate('nullable')]
    public ?string $tgl_sk_cpns = null;

    #[Validate('nullable')]
    public ?string $tmt_cpns = null;

    #[Validate('nullable')]
    public ?string $nomor_sk_pns = null;

    #[Validate('nullable')]
    public ?string $tgl_sk_pns = null;

    #[Validate('nullable')]
    public ?string $tmt_pns = null;

    #[Validate('nullable')]
    public ?string $no_sk_dpk_penugasan_kontrak = null;

    #[Validate('nullable')]
    public ?string $tgl_sk_dpk_penugasan_kontrak = null;

    #[Validate('nullable')]
    public ?string $keterangan = null;

    #[Validate('nullable')]
    public ?string $tgl_kgb_terakhir = null;

    public function setPegawai($pegawai)
    {
        $this->fill($pegawai->toArray());
    }
}
