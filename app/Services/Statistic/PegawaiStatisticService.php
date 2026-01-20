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

    public function getJenisKelaminChart(?string $kabKota = null): array
    {
        $data = Pegawai::when($kabKota, fn ($q) => $q->where('kab_kota', $kabKota))
            ->selectRaw('jenis_kelamin, COUNT(*) as total')
            ->groupBy('jenis_kelamin')
            ->pluck('total', 'jenis_kelamin');

        return [
            'labels' => $data->keys()->toArray(),
            'values' => $data->values()->toArray(),
        ];
    }

    public function getTingkatPendidikanChart(?string $kabKota = null): array
    {
        $data = Pegawai::when($kabKota, fn ($q) => $q->where('kab_kota', $kabKota))
            ->selectRaw('tingkat_pendidikan_nama, COUNT(*) as total')
            ->groupBy('tingkat_pendidikan_nama')
            ->orderByDesc('total')
            ->pluck('total', 'tingkat_pendidikan_nama');

        return [
            'labels' => $data->keys()->toArray(),
            'values' => $data->values()->toArray(),
        ];
    }

    public function getJenisJabatanChart(?string $kabKota = null): array
    {
        $data = Pegawai::when($kabKota, fn ($q) => $q->where('kab_kota', $kabKota))
            ->selectRaw('jenis_jabatan_nama, COUNT(*) as total')
            ->groupBy('jenis_jabatan_nama')
            ->orderByDesc('total')
            ->pluck('total', 'jenis_jabatan_nama');

        return [
            'labels' => $data->keys()->toArray(),
            'values' => $data->values()->toArray(),
        ];
    }

    public function getAllStats(?string $kabKota = null): array
    {
        return [
            'total' => $this->getTotalPegawai($kabKota),
            'pppk' => $this->getPegawaiPPPK($kabKota),
            'organik' => $this->getPegawaiOrganik($kabKota),
            'dpk' => $this->getPegawaiDPK($kabKota),
            'ppnpn' => $this->getPegawaiPPNPN($kabKota),
            'jenis_kelamin_chart' => $this->getJenisKelaminChart($kabKota),
            'pendidikan_chart' => $this->getTingkatPendidikanChart($kabKota),
            'jenis_jabatan_chart' => $this->getJenisJabatanChart($kabKota),
        ];
    }
}
