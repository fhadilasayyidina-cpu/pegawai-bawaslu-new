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

    #[Validate('nullable|string|url')]
    public ?string $sk_cpns_drive_link = null;

    #[Validate('nullable')]
    public ?string $tgl_sk_cpns = null;

    #[Validate('nullable')]
    public ?string $tmt_cpns = null;

    #[Validate('nullable')]
    public ?string $nomor_sk_pns = null;

    #[Validate('nullable|string|url')]
    public ?string $sk_pns_drive_link = null;

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

    #[Validate('nullable|string|url')]
    public ?string $sk_kgb_drive_link = null;

    #[Validate('nullable|string|url')]
    public ?string $karpeg_drive_link = null;

    #[Validate('nullable|string|url')]
    public ?string $npwp_drive_link = null;

    #[Validate('nullable|string|url')]
    public ?string $bpjs_drive_link = null;

    public function setPegawai($pegawai)
    {
        $data = $pegawai->toArray();

        // Format date fields as Y-m-d for HTML5 date inputs
        $dateFields = [
            'tgl_sk_cpns', 'tmt_cpns', 'tgl_sk_pns', 'tmt_pns',
            'tgl_sk_dpk_penugasan_kontrak', 'tgl_kgb_terakhir',
        ];

        foreach ($dateFields as $field) {
            if (isset($data[$field]) && $data[$field] !== null) {
                $data[$field] = $pegawai->{$field}->format('Y-m-d');
            }
        }

        $this->fill($data);
    }
}
