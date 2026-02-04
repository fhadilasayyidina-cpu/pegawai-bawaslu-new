<?php

namespace App\Services\Cuti;

use App\Models\Pegawai;

class CutiAlasanPentingService
{
    /**
     * Validasi cuti alasan penting berdasarkan aturan PP 11/2017.
     * - Maksimal 2 bulan (60 hari)
     * - Untuk kepentingan pribadi yang mendesak
     * - Contoh: kematian keluarga, pernikahan anak, dll.
     */
    public function validasiCutiAlasanPenting(int $lamaHari, ?string $alasan = null): array
    {
        $maxHari = 60; // 2 bulan

        if ($lamaHari > $maxHari) {
            return [
                'valid' => false,
                'pesan' => "Cuti alasan penting maksimal 2 bulan (60 hari). Permintaan: {$lamaHari} hari.",
            ];
        }

        if (empty($alasan)) {
            return [
                'valid' => false,
                'pesan' => 'Harap mencantumkan alasan penting untuk cuti.',
            ];
        }

        return [
            'valid' => true,
            'pesan' => 'Cuti alasan penting dapat disetujui.',
        ];
    }

    /**
     * Get info cuti alasan penting untuk pegawai.
     */
    public function getInfoCutiAlasanPenting(Pegawai $pegawai): array
    {
        return [
            'layak' => true,
            'keterangan' => 'Cuti untuk kepentingan pribadi yang mendesak.',
            'aturan' => [
                'Durasi maksimal' => '2 bulan (60 hari)',
                'Contoh alasan' => 'Kematian keluarga, pernikahan anak, istri/suami melahirkan, dll.',
                'Syarat' => 'Bukti pendukung yang sah',
            ],
        ];
    }
}
