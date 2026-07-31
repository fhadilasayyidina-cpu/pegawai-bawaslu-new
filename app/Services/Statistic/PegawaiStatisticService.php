<?php

namespace App\Services\Statistic;

use App\Cache\PegawaiCache;
use Illuminate\Support\Facades\Cache;
use App\Models\Pegawai;
use App\Enums\JenisPegawai;
use Illuminate\Support\Facades\Log;

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

        return Cache::remember(
            PegawaiCache::dashboardStats(),
            now()->addHours(6),
            function () use ($kabKota) {

                $start = microtime(true);

                $summary = $this->getSummary($kabKota);



                $jenisKelamin = $this->getJenisKelaminChart($kabKota);



                $pendidikan = $this->getTingkatPendidikanChart($kabKota);



                $jabatan = $this->getJenisJabatanChart($kabKota);



                $umur = $this->getRangeUmurChart($kabKota);

                // Hitung durasi eksekusi dalam milidetik (ms)
                $duration = round((microtime(true) - $start) * 1000, 2);
                $threshold = 100; // Threshold dalam ms (misal: 100 ms)

                // Log warning HANYA jika eksekusi melebihi threshold
                if ($duration > $threshold) {
                    Log::warning("PERFORMANCE WARNING: getAllStats() memakan waktu terlalu lama!", [
                        'duration' => "{$duration} ms",
                        'threshold' => "{$threshold} ms",
                        'filter_kab_kota' => $kabKota ?? 'semua',
                    ]);
                }



                return [
                    ...$summary,
                    'jenis_kelamin_chart' => $jenisKelamin,
                    'pendidikan_chart' => $pendidikan,
                    'jenis_jabatan_chart' => $jabatan,
                    'range_umur_chart' => $umur,
                ];
            }
        );
    }
}
