<?php

namespace App\Services\Absensi;

use App\Models\Absensi;

class AbsensiStatisticService
{
    /**
     * Get statistics for absensi based on filters.
     *
     * @param  int|null  $pegawaiId  Filter by pegawai ID
     * @param  string|null  $tanggalMulai  Filter by start date
     * @param  string|null  $tanggalAkhir  Filter by end date
     * @return array{total: int, hadir: int, izin: int, cuti: int, tidak_hadir: int}
     */
    public function getStatistics(?int $pegawaiId = null, ?string $tanggalMulai = null, ?string $tanggalAkhir = null): array
    {
        $query = Absensi::query();

        if ($pegawaiId) {
            $query->where('pegawai_id', $pegawaiId);
        }

        if ($tanggalMulai) {
            $query->where('tanggal', '>=', $tanggalMulai);
        }

        if ($tanggalAkhir) {
            $query->where('tanggal', '<=', $tanggalAkhir);
        }

        $total = $query->count();

        return [
            'total' => $total,
            'hadir' => (clone $query)->where('status', 'Hadir')->count(),
            'izin' => (clone $query)->where('status', 'Izin')->count(),
            'cuti' => (clone $query)->where('status', 'Cuti')->count(),
            'tidak_hadir' => (clone $query)->where('status', 'Tidak Hadir')->count(),
        ];
    }
}
