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

    #[Validate('nullable|numeric')]
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

    #[Validate('nullable|numeric')]
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
        $data = $pegawai->toArray();

        // Format date fields as Y-m-d for HTML5 date inputs
        // Note: tmt_golongan and tmt_jabatan already cast as 'date:Y-m-d' in model
        // but we ensure proper format here for consistency
        if (isset($data['tmt_golongan']) && $data['tmt_golongan'] !== null) {
            $data['tmt_golongan'] = $pegawai->tmt_golongan->format('Y-m-d');
        }
        if (isset($data['tmt_jabatan']) && $data['tmt_jabatan'] !== null) {
            $data['tmt_jabatan'] = $pegawai->tmt_jabatan->format('Y-m-d');
        }

        $this->fill($data);
    }
}
