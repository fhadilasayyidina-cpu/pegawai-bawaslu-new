<?php

namespace App\Services\Absensi;

use App\Enums\JenisAbsen;
use App\Enums\StatusAbsensi;
use App\Models\Absensi;
use App\Models\Pegawai;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Rap2hpoutre\FastExcel\Facades\FastExcel;

class ImportAbsensiService
{
    public function downloadTemplateImportAbsensiId()
    {
        return Storage::disk('public')->download('templates/id-absensi-template.xlsx');
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
            throw new Exception('Gagal memproses import data: '.$e->getMessage());
        }
    }

    public function import($filepath, $createdById, $kabKota = null): array
    {
        // By default, import WFO
        return $this->importAbsenWfo($filepath, $createdById, $kabKota);
    }

    public function importAbsenWfo($filepath, $createdById, $kabKota = null): array
    {
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

            FastExcel::import($filepath, function ($line) use (&$imported, &$skipped, &$failed, &$errors, $createdById, $kabKota) {
                $noId = $this->getRowValue($line, ['no. id', 'noid', 'idabsensi', 'id_absensi', 'id']);
                $tanggalRaw = $this->getRowValue($line, ['tanggal', 'date', 'tgl']);
                $scanMasukRaw = $this->getRowValue($line, ['scan masuk', 'scanmasuk', 'masuk', 'in']);
                $scanPulangRaw = $this->getRowValue($line, ['scan pulang', 'scanpulang', 'pulang', 'out']);

                if (empty($noId) || empty($tanggalRaw)) {
                    $skipped++;

                    return;
                }

                // Find Pegawai by id_absensi
                $query = Pegawai::where('id_absensi', trim((string) $noId));
                if ($kabKota) {
                    $query->where('kab_kota', $kabKota);
                }
                $pegawai = $query->first();

                if (! $pegawai) {
                    $failed++;
                    $errors[] = "Pegawai dengan ID Absensi '{$noId}' tidak ditemukan".($kabKota ? ' di wilayah filter tersebut.' : '.');

                    return;
                }

                // Parse Date
                $tanggal = null;
                try {
                    if ($tanggalRaw instanceof \DateTime) {
                        $tanggal = Carbon::instance($tanggalRaw);
                    } else {
                        $tanggalStr = trim((string) $tanggalRaw);
                        if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $tanggalStr)) {
                            $tanggal = Carbon::createFromFormat('d/m/Y', $tanggalStr);
                        } else {
                            $tanggal = Carbon::parse($tanggalStr);
                        }
                    }
                } catch (Exception $e) {
                    $failed++;
                    $errors[] = "Format tanggal tidak valid untuk ID '{$noId}': '{$tanggalRaw}'";

                    return;
                }

                $scanMasuk = $this->parseTime($scanMasukRaw);
                $scanPulang = $this->parseTime($scanPulangRaw);

                // Set status based on scan masuk presence
                $status = ! empty($scanMasuk) ? StatusAbsensi::HADIR : StatusAbsensi::BOLOS;

                Absensi::updateOrCreate(
                    [
                        'pegawai_id' => $pegawai->id,
                        'tanggal' => $tanggal->format('Y-m-d'),
                    ],
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
                'message' => "Import data absensi WFO berhasil. {$imported} data diimport, {$skipped} dilewati, {$failed} gagal.",
                'imported' => $imported,
                'skipped' => $skipped,
                'failed' => $failed,
                'errors' => $errors,
            ];
        } catch (Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Gagal memproses import data: '.$e->getMessage(),
                'imported' => $imported,
                'skipped' => $skipped,
                'failed' => $failed,
                'errors' => [$e->getMessage()],
            ];
        }
    }

    public function importAbsenWfh($filepath, $createdById, $kabKota = null): array
    {
        return [
            'success' => false,
            'message' => 'Metode import WFH belum diimplementasikan.',
            'imported' => 0,
            'skipped' => 0,
            'failed' => 0,
            'errors' => [],
        ];
    }

    private function getRowValue(array $row, array $possibleKeys)
    {
        foreach ($row as $key => $value) {
            $normalizedKey = strtolower(str_replace([' ', '_', '.', '(', ')', '-'], '', $key));
            foreach ($possibleKeys as $possibleKey) {
                $normalizedPossible = strtolower(str_replace([' ', '_', '.', '(', ')', '-'], '', $possibleKey));
                if ($normalizedKey === $normalizedPossible || str_contains($normalizedKey, $normalizedPossible)) {
                    return $value;
                }
            }
        }

        return null;
    }

    private function parseTime($timeRaw): ?string
    {
        if (empty($timeRaw)) {
            return null;
        }
        if ($timeRaw instanceof \DateTime) {
            return $timeRaw->format('H:i:s');
        }
        if (is_numeric($timeRaw)) {
            $seconds = round($timeRaw * 86400);
            $hours = floor($seconds / 3600);
            $mins = floor(($seconds - ($hours * 3600)) / 60);
            $secs = $seconds % 60;

            return sprintf('%02d:%02d:%02d', $hours, $mins, $secs);
        }
        try {
            return Carbon::parse(trim((string) $timeRaw))->format('H:i:s');
        } catch (Exception $e) {
            return null;
        }
    }
}
