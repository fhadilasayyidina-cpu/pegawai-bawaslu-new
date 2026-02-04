<?php

namespace App\Services\Cuti;

use App\Models\Pegawai;

class CutiMelahirkanService
{
    /**
     * Validasi cuti melahirkan berdasarkan aturan PP 11/2017.
     * - 3 bulan sebelum + 3 bulan sesudah = total 6 bulan (180 hari)
     */
    public function validasiCutiMelahirkan(int $lamaHari): array
    {
        $maxHari = 180; // 6 bulan

        if ($lamaHari > $maxHari) {
            return [
                'valid' => false,
                'pesan' => "Cuti melahirkan maksimal 6 bulan (180 hari). Permintaan: {$lamaHari} hari.",
            ];
        }

        return [
            'valid' => true,
            'pesan' => 'Cuti melahirkan dapat disetujui.',
        ];
    }

    /**
     * Get info cuti melahirkan untuk pegawai.
     */
    public function getInfoCutiMelahirkan(Pegawai $pegawai): array
    {
        return [
            'layak' => true,
            'keterangan' => 'Cuti melahirkan 3 bulan sebelum dan 3 bulan sesudah.',
            'aturan' => [
                'Durasi maksimal' => '6 bulan (180 hari)',
                'Syarat' => 'Surat keterangan dokter/bidan',
            ],
        ];
    }
}
