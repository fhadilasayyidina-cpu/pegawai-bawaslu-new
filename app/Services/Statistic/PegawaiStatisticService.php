<?php

namespace App\Services\Statistic;

use Illuminate\Support\Facades\Cache;
use App\Models\Pegawai;
use App\Enums\JenisPegawai;

class PegawaiStatisticService
{
    public function getSummary(?string $kabKota = null): array
    {
        $data = Pegawai::when(
            $kabKota,
            fn($q) =>
            $q->where('kab_kota', $kabKota)
        )
            ->selectRaw("
            COUNT(*) as total,
            SUM(jenis_pegawai = ?) as pns_organik,
            SUM(jenis_pegawai = ?) as pppk,
            SUM(jenis_pegawai = ?) as pns_dpk,
            SUM(jenis_pegawai = ?) as ppnpn
        ", [
                JenisPegawai::PNS_ORGANIK->value,
                JenisPegawai::PPPK->value,
                JenisPegawai::PNS_DPK->value,
                JenisPegawai::PPNPN->value,
            ])
            ->first();

        return [
            'total' => (int) $data->total,
            'organik' => (int) $data->pns_organik,
            'pppk' => (int) $data->pppk,
            'dpk' => (int) $data->pns_dpk,
            'ppnpn' => (int) $data->ppnpn,
        ];
    }

    public function getJenisKelaminChart(?string $kabKota = null): array
    {
        $data = Pegawai::when($kabKota, fn($q) => $q->where('kab_kota', $kabKota))
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
        $data = Pegawai::when($kabKota, fn($q) => $q->where('kab_kota', $kabKota))
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
        $data = Pegawai::when($kabKota, fn($q) => $q->where('kab_kota', $kabKota))
            ->selectRaw('jenis_jabatan_nama, COUNT(*) as total')
            ->groupBy('jenis_jabatan_nama')
            ->orderByDesc('total')
            ->pluck('total', 'jenis_jabatan_nama');

        return [
            'labels' => $data->keys()->toArray(),
            'values' => $data->values()->toArray(),
        ];
    }

    public function getRangeUmurChart(?string $kabKota = null): array
    {
        $data = Pegawai::when($kabKota, fn($q) => $q->where('kab_kota', $kabKota))
            ->selectRaw('range_umur, COUNT(*) as total')
            ->whereNotNull('range_umur')
            ->groupBy('range_umur')
            ->orderBy('range_umur')
            ->pluck('total', 'range_umur');

        return [
            'labels' => $data->keys()->toArray(),
            'values' => $data->values()->toArray(),
        ];
    }

    public function getAllStats(?string $kabKota = null): array
    {
        $cacheKey = 'dashboard_stats_' . ($kabKota ?: 'semua');

        return Cache::remember(
            $cacheKey,
            now()->addHours(6),
            function () use ($kabKota) {

                $summary = $this->getSummary($kabKota);

                return [
                    ...$summary,
                    'jenis_kelamin_chart' => $this->getJenisKelaminChart($kabKota),
                    'pendidikan_chart' => $this->getTingkatPendidikanChart($kabKota),
                    'jenis_jabatan_chart' => $this->getJenisJabatanChart($kabKota),
                    'range_umur_chart' => $this->getRangeUmurChart($kabKota),
                ];
            }
        );
    }
}
