<?php

namespace App\Services\Absensi;

use App\Enums\JenisAbsen;
use App\Enums\StatusAbsensi;
use App\Helpers\Absensi\AbsensiStatusHelper;
use App\Models\Absensi;
use App\Models\Pegawai;
use App\Support\ExcelHelper as SupportExcelHelper;
use Carbon\Carbon;
use ExcelHelper;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Rap2hpoutre\FastExcel\Facades\FastExcel;

class ImportAbsensiService
{

    public function __construct(
        private ImportAbsensiWfhService $importWfhAbsensiService
    ) {}

    public function downloadTemplateImportAbsensiId()
    {
        return Storage::disk('public')->download('templates/id-absensi-template.xlsx');
    }



    private function parseDate($tanggalRaw): Carbon
    {
        if ($tanggalRaw instanceof \DateTime) {
            return Carbon::instance($tanggalRaw);
        }
        $tanggalStr = trim((string) $tanggalRaw);
        if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $tanggalStr)) {
            return Carbon::createFromFormat('d/m/Y', $tanggalStr);
        }
        return Carbon::parse($tanggalStr);
    }

    private function parseTime($timeRaw): ?string
    {
        if (blank($timeRaw)) {
            return null;
        }

        // Jika dari Excel sudah berupa DateTime
        if ($timeRaw instanceof \DateTimeInterface) {
            return Carbon::instance($timeRaw)->format('H:i:s');
        }

        // Jika dari Excel berupa angka serial waktu
        if (is_numeric($timeRaw)) {
            $seconds = (int) round($timeRaw * 86400);

            $hours = floor($seconds / 3600);
            $minutes = floor(($seconds % 3600) / 60);
            $secs = $seconds % 60;

            return sprintf('%02d:%02d:%02d', $hours, $minutes, $secs);
        }

        // Jika string biasa (07:30, 7:30 AM, dst)
        try {
            return Carbon::parse(trim((string) $timeRaw))->format('H:i:s');
        } catch (Exception $e) {
            return null;
        }
    }




    /**
     * Proses import absensi dengan format mesin fingerprint.
     *
     * Saat ini hanya digunakan untuk WFO.
     * Import WFH memiliki format Excel berbeda sehingga diproses
     * melalui ImportWfhAbsensiService.
     */
    private function importAbsensiByType(
        string $filepath,
        int $createdById,
        JenisAbsen $jenisAbsen,
        ?string $kabKota = null
    ): array {

        Log::debug('Filter kabupaten import:', [
            'kabKota' => $kabKota,
        ]);

        if (! file_exists($filepath)) {
            return [
                'success' => false,
                'message' => "File tidak ditemukan di path: {$filepath}",
                'imported' => 0,
                'skipped' => 0,
                'failed' => 0,
                'errors' => ['File tidak ditemukan.'],
            ];
        }

        $imported = 0;
        $skipped = 0;
        $failed = 0;
        $errors = [];

        try {
            DB::beginTransaction();

            $pegawaiMap = Pegawai::query()
                ->when($kabKota, fn($q) => $q->where('kab_kota', $kabKota))
                ->get(['id', 'nip_baru', 'id_absensi'])->keyBy(fn($p) => trim((string) $p->id_absensi));
            Log::debug('Pegawai map import:', [
                'kabKota' => $kabKota,
                'total' => $pegawaiMap->count(),
                'sample' => $pegawaiMap->take(5)->keys()->toArray(),
            ]);
            FastExcel::import($filepath, function ($line) use (
                &$imported,
                &$skipped,
                &$failed,
                &$errors,
                $createdById,
                $kabKota,
                $jenisAbsen,
                $pegawaiMap,
            ) {

                $row = SupportExcelHelper::normalizeRow($line);
                $noId = $row['idabsensi']
                    ?? $row['noid']
                    ?? $row['id']
                    ?? null;

                $tanggalRaw = $row['tanggal']
                    ?? $row['tgl']
                    ?? $row['date']
                    ?? null;

                $scanMasukRaw = $row['scanmasuk']
                    ?? $row['masuk']
                    ?? $row['in']
                    ?? null;

                $scanPulangRaw = $row['scanpulang']
                    ?? $row['pulang']
                    ?? $row['out']
                    ?? null;


                if (empty($noId) || empty($tanggalRaw)) {
                    $skipped++;
                    return;
                }



                // Parse tanggal
                try {
                    $tanggal = $this->parseDate($tanggalRaw);
                } catch (Exception $e) {
                    $failed++;
                    $errors[] = "Format tanggal tidak valid untuk ID '{$noId}': '{$tanggalRaw}'";
                    return;
                }

                $scanMasuk = $this->parseTime($scanMasukRaw);
                $scanPulang = $this->parseTime($scanPulangRaw);

                $status = AbsensiStatusHelper::determine($scanMasuk, $scanPulang);

                $pegawai = $pegawaiMap[trim((string) $noId)] ?? null;

                if (! $pegawai) {
                    $failed++;
                    $errors[] = "Pegawai dengan ID Absensi 
                    '{$noId}' tidak ditemukan" . ($kabKota ? ' di wilayah filter tersebut.' : '.');
                    return;
                }

                Absensi::updateOrCreate(
                    ['pegawai_id' => $pegawai->id, 'tanggal' => $tanggal
                        ->format('Y-m-d'),],
                    [
                        'nip' => $pegawai->nip_baru,
                        'scan_masuk' => $scanMasuk,
                        'scan_pulang' => $scanPulang,
                        'status' => $status,
                        'jenis_absen' => JenisAbsen::WFO,
                        'created_by' => $createdById,
                    ]
                );

                $imported++;
            });

            DB::commit();

            return [
                'success' => true,
                'message' => "Import data absensi {$jenisAbsen->value} berhasil. {$imported} data diimport, {$skipped} dilewati, {$failed} gagal.",
                'imported' => $imported,
                'skipped' => $skipped,
                'failed' => $failed,
                'errors' => $errors,
            ];
        } catch (Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Gagal memproses import data: ' . $e->getMessage(),
                'imported' => $imported,
                'skipped' => $skipped,
                'failed' => $failed,
                'errors' => [$e->getMessage()],
            ];
        }
    }



    public function importAbsensiId($filepath)
    {
        if (! file_exists($filepath)) {
            throw new Exception("File tidak ditemukan di path: {$filepath}");
        }

        $updatedCount = 0;
        $notFoundNip = [];
        $skippedCount = 0;

        DB::beginTransaction();
        try {
            FastExcel::import($filepath, function ($line) use (&$updatedCount, &$notFoundNip, &$skippedCount) {
                $normalizedLine = array_change_key_case($line, CASE_LOWER);

                $nip = isset($normalizedLine['nip']) ? trim((string) $normalizedLine['nip']) : null;
                $idAbsensi = isset($normalizedLine['id_absensi']) ? trim((string) $normalizedLine['id_absensi']) : null;

                if (empty($nip) || empty($idAbsensi)) {
                    $skippedCount++;

                    return;
                }

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
                'status' => true,
                'message' => "Berhasil memperbarui {$updatedCount} data ID Absensi pegawai.",
                'total_updated' => $updatedCount,
                'total_skipped' => $skippedCount,
                'not_found_nip' => $notFoundNip,
            ];
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Gagal memproses import data: ' . $e->getMessage());
        }
    }





    public function importAbsenWfo($filepath, $createdById, $kabKota = null): array
    {
        return $this->importAbsensiByType(
            $filepath,
            $createdById,
            JenisAbsen::WFO,
            $kabKota
        );
    }



    public function importAbsenWfh(
        $filepath,
        $createdById,
        $kabKota = null
    ): array {
        return $this->importWfhAbsensiService->import(
            $filepath,
            $createdById,
            $kabKota
        );
    }
}
