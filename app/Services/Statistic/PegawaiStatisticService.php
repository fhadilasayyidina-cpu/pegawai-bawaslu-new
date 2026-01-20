<?php

namespace App\Services\Statistic;

use App\Models\Pegawai;

class PegawaiStatisticService
{
    public function getTotalPegawai(?string $kabKota = null): int
    {
        return Pegawai::when($kabKota, fn ($q) => $q->where('kab_kota', $kabKota))
            ->count();
    }

    public function getPegawaiOrganik(?string $kabKota = null): int
    {
        return Pegawai::when($kabKota, fn ($q) => $q->where('kab_kota', $kabKota))
            ->where('jenis_pegawai', 'like', '%organik%')
            ->count();
    }

    public function getPegawaiDPK(?string $kabKota = null): int
    {
        return Pegawai::when($kabKota, fn ($q) => $q->where('kab_kota', $kabKota))
            ->where('jenis_pegawai', 'like', '%dpk%')
            ->count();
    }

    public function getPegawaiPPPK(?string $kabKota = null): int
    {
        return Pegawai::when($kabKota, fn ($q) => $q->where('kab_kota', $kabKota))
            ->where('jenis_pegawai', 'like', '%PPPK%')
            ->count();
    }

    public function getPegawaiPPNPN(?string $kabKota = null): int
    {
        return Pegawai::when($kabKota, fn ($q) => $q->where('kab_kota', $kabKota))
            ->where('jenis_pegawai', 'like', '%PPNPN%')
            ->count();
    }

    public function getAllStats(?string $kabKota = null): array
    {
        return [
            'total' => $this->getTotalPegawai($kabKota),
            'pppk' => $this->getPegawaiPPPK($kabKota),
            'organik' => $this->getPegawaiOrganik($kabKota),
            'dpk' => $this->getPegawaiDPK($kabKota),
            'ppnpn' => $this->getPegawaiPPNPN($kabKota),
        ];
    }
}
