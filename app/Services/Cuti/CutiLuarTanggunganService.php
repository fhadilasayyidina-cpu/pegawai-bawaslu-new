<?php

namespace App\Services\Cuti;

use App\Models\Pegawai;

class CutiLuarTanggunganService
{
    /**
     * Validasi cuti di luar tanggungan negara berdasarkan aturan PP 11/2017.
     * - Maksimal 3 tahun (1095 hari)
     * - Tanpa gaji selama cuti
     * - Untuk kepentingan pribadi
     */
    public function validasiCutiLuarTanggungan(int $lamaHari, ?string $alasan = null): array
    {
        $maxHari = 1095; // 3 tahun

        if ($lamaHari > $maxHari) {
            return [
                'valid' => false,
                'pesan' => "Cuti di luar tanggungan maksimal 3 tahun (1095 hari). Permintaan: {$lamaHari} hari.",
            ];
        }

        if (empty($alasan)) {
            return [
                'valid' => false,
                'pesan' => 'Harap mencantumkan alasan untuk cuti.',
            ];
        }

        return [
            'valid' => true,
            'pesan' => 'Cuti di luar tanggungan negara dapat disetujui.',
            'warning' => '⚠️ Pegawai tidak menerima gaji selama cuti.',
        ];
    }

    /**
     * Get info cuti luar tanggungan untuk pegawai.
     */
    public function getInfoCutiLuarTanggungan(Pegawai $pegawai): array
    {
        return [
            'layak' => true,
            'keterangan' => 'Cuti untuk kepentingan pribadi dengan tanggungan sendiri.',
            'aturan' => [
                'Durasi maksimal' => '3 tahun (1095 hari)',
                'Gaji' => 'Tidak menerima gaji selama cuti',
                'Syarat' => 'Persetujuan pejabat yang berwenang',
            ],
        ];
    }
}
