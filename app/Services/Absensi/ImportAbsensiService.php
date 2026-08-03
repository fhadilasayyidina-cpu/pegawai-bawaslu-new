<?php

namespace App\Services\Absensi;

use App\Models\Absensi;
use App\Models\Pegawai;
use Exception;
use Rap2hpoutre\FastExcel\Facades\FastExcel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ImportAbsensiService
{
    public function downloadTemplateImportAbsensiId()
    {
        return Storage::disk('public')->download('templates/id-absensi-template.xlsx');
    }

    public function importAbsensiId($filepath)
    {
        if (!file_exists($filepath)) {
            throw new Exception("File tidak ditemukan di path: {$filepath}");
        }

        $updatedCount = 0;
        $notFoundNip = [];
        $skippedCount = 0;

        DB::beginTransaction();
        try {
            // FastExcel akan meloop setiap baris sebagai Associative Array
            // Key array sesuai dengan header kolom di Excel (misal: $line['nip'])
            FastExcel::import($filepath, function ($line) use (&$updatedCount, &$notFoundNip, &$skippedCount) {

                // Normalisasi kunci array ke lowercase agar aman dari variasi penulisan ("NIP", "nip", "Nip")
                $normalizedLine = array_change_key_case($line, CASE_LOWER);

                $nip = isset($normalizedLine['nip']) ? trim((string)$normalizedLine['nip']) : null;
                $idAbsensi = isset($normalizedLine['id_absensi']) ? trim((string)$normalizedLine['id_absensi']) : null;

                // Lewati baris jika NIP atau ID Absensi kosong/tidak diisi
                if (empty($nip) || empty($idAbsensi)) {
                    $skippedCount++;
                    return;
                }

                // Cari pegawai berdasarkan NIP dan update id_absensi
                $pegawai = Pegawai::where('nip_baru', $nip)->first();

                if ($pegawai) {
                    $pegawai->update([
                        'id_absensi' => $idAbsensi,
                    ]);
                    $updatedCount++;
                } else {
                    $notFoundNip[] = $nip;
                }
            });

            DB::commit();

            return [
                'status'        => true,
                'message'       => "Berhasil memperbarui {$updatedCount} data ID Absensi pegawai.",
                'total_updated' => $updatedCount,
                'total_skipped' => $skippedCount,
                'not_found_nip' => $notFoundNip,
            ];
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception("Gagal memproses import data: " . $e->getMessage());
        }
    }
}
