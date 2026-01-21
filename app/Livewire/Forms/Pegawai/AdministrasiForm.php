<?php

namespace App\Livewire\Forms\Pegawai;

use Livewire\Form;

class AdministrasiForm extends Form
{
    public $npwp_nomor;

    public $bpjs;

    public $kartu_pegawai;

    public $nomor_sk_cpns;

    public $tgl_sk_cpns;

    public $tmt_cpns;

    public $nomor_sk_pns;

    public $tgl_sk_pns;

    public $tmt_pns;

    public $no_sk_dpk_penugasan_kontrak;

    public $tgl_sk_dpk_penugasan_kontrak;

    public $keterangan;

    public function setPegawai($pegawai)
    {
        $this->fill($pegawai->toArray());
    }
}
