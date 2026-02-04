<?php

namespace App\Services\Cuti;

use App\Models\Pegawai;

class CutiSakitService
{
    /**
     * Validasi cuti sakit berdasarkan aturan PP 11/2017.
     * - Sakit > 2 hari s/d 14 hari: wajib surat dokter
     * - Sakit > 14 hari: wajib surat dokter pemerintah
     */
    public function validasiCutiSakit(int $lamaHari, ?string $statusDokter = null, ?string $nomorSuratDokter = null): array
    {
        // Sakit 1-2 hari: tidak perlu surat dokter
        if ($lamaHari <= 2) {
            return [
                'valid' => true,
                'pesan' => 'Cuti sakit dapat disetujui.',
            ];
        }

        // Sakit > 2 hari s/d 14 hari: wajib surat dokter
        if ($lamaHari > 2 && $lamaHari <= 14) {
            if (! $statusDokter || ! $nomorSuratDokter) {
                return [
                    'valid' => false,
                    'pesan' => 'Cuti sakit lebih dari 2 hari wajib melampirkan surat dokter.',
                ];
            }
        }

        // Sakit > 14 hari: wajib surat dokter pemerintah
        if ($lamaHari > 14) {
            if ($statusDokter !== 'pemerintah' || ! $nomorSuratDokter) {
                return [
                    'valid' => false,
                    'pesan' => 'Cuti sakit lebih dari 14 hari wajib melampirkan surat dokter pemerintah.',
                ];
            }
        }

        return [
            'valid' => true,
            'pesan' => 'Cuti sakit dapat disetujui.',
        ];
    }

    /**
     * Get info cuti sakit untuk pegawai.
     */
    public function getInfoCutiSakit(Pegawai $pegawai): array
    {
        return [
            'layak' => true, // Cuti sakit tidak ada batasan khusus
            'keterangan' => 'Cuti sakit dengan surat dokter yang sah.',
            'aturan' => [
                'Sakit 1-2 hari' => 'Tidak perlu surat dokter',
                'Sakit 3-14 hari' => 'Wajib surat dokter',
                'Sakit > 14 hari' => 'Wajib surat dokter pemerintah',
            ],
        ];
    }
}
