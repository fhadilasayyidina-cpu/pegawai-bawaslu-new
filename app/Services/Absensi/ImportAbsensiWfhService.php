<?php

namespace App\Services\Absensi;

use App\Enums\JenisAbsen;
use App\Models\Absensi;
use App\Models\Pegawai;
use App\Support\Absensi\AbsensiStatusHelper;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Rap2hpoutre\FastExcel\Facades\FastExcel;

class ImportAbsensiWfhService
{
    public function import(
        string $filepath,
        int $createdById,
        ?string $kabKota = null
    ): array {

        $dataAbsensi = [];
        $failed = 0;
        $skipped = 0;
        $errors = [];

        try {

            DB::beginTransaction();

            FastExcel::import($filepath, function ($line) use (
                &$dataAbsensi,
                &$failed,
                &$skipped,
                &$errors
            ) {

                $timestampRaw = $line['Timestamp'] ?? null;
                $kehadiran = $line['Kehadiran'] ?? null;
                $nipRaw = $line['NIP'] ?? null;
                Log::debug('ImportAbsensiWfhService: Processing line', [
                    'timestampRaw' => $timestampRaw,
                    'kehadiran' => $kehadiran,
                    'nipRaw' => $nipRaw,
                ]);


                if (
                    empty($timestampRaw) ||
                    empty($kehadiran) ||
                    empty($nipRaw)
                ) {
                    $skipped++;
                    return;
                }


                try {

                    $timestamp = Carbon::parse($timestampRaw);
                } catch (Exception $e) {

                    $failed++;
                    $errors[] = "Timestamp tidak valid: {$timestampRaw}";
                    return;
                }


                $nip = $this->cleanNip($nipRaw);


                if (! $nip) {
                    $skipped++;
                    return;
                }


                $key = $nip . '-' . $timestamp->format('Y-m-d');


                if (! isset($dataAbsensi[$key])) {

                    $dataAbsensi[$key] = [
                        'nip' => $nip,
                        'tanggal' => $timestamp->format('Y-m-d'),
                        'scan_masuk' => null,
                        'scan_pulang' => null,
                    ];
                }


                $jam = $timestamp->format('H:i:s');


                if ($kehadiran === 'Check In') {

                    if (
                        ! $dataAbsensi[$key]['scan_masuk'] ||
                        $jam < $dataAbsensi[$key]['scan_masuk']
                    ) {
                        $dataAbsensi[$key]['scan_masuk'] = $jam;
                    }
                }


                if ($kehadiran === 'Check Out') {

                    if (
                        ! $dataAbsensi[$key]['scan_pulang'] ||
                        $jam > $dataAbsensi[$key]['scan_pulang']
                    ) {
                        $dataAbsensi[$key]['scan_pulang'] = $jam;
                    }
                }
            });


            $pegawaiMap = Pegawai::query()
                ->whereIn(
                    'nip_baru',
                    collect($dataAbsensi)
                        ->pluck('nip')
                        ->unique()
                )
                ->when(
                    $kabKota,
                    fn($q) => $q->where('kab_kota', $kabKota)
                )
                ->get()
                ->keyBy('nip_baru');



            $imported = 0;


            foreach ($dataAbsensi as $absen) {


                $pegawai = $pegawaiMap[$absen['nip']] ?? null;


                if (! $pegawai) {

                    $failed++;
                    $errors[] =
                        "Pegawai dengan NIP {$absen['nip']} tidak ditemukan";

                    continue;
                }


                Absensi::updateOrCreate(
                    [
                        'pegawai_id' => $pegawai->id,
                        'tanggal' => $absen['tanggal'],
                    ],
                    [
                        'nip' => $pegawai->nip_baru,
                        'scan_masuk' => $absen['scan_masuk'],
                        'scan_pulang' => $absen['scan_pulang'],
                        'status' => AbsensiStatusHelper::determine(
                            $absen['scan_masuk'],
                            $absen['scan_pulang']
                        ),
                        'jenis_absen' => JenisAbsen::WFH,
                        'created_by' => $createdById,
                    ]
                );


                $imported++;
            }


            DB::commit();


            return [
                'success' => true,
                'message' => "Import WFH berhasil. {$imported} data diproses.",
                'imported' => $imported,
                'skipped' => $skipped,
                'failed' => $failed,
                'errors' => $errors,
            ];
        } catch (Exception $e) {

            DB::rollBack();

            return [
                'success' => false,
                'message' => $e->getMessage(),
                'imported' => 0,
                'skipped' => $skipped,
                'failed' => $failed,
                'errors' => [$e->getMessage()],
            ];
        }
    }


    private function cleanNip(?string $nip): ?string
    {
        if (! $nip) {
            return null;
        }

        return preg_replace('/[^0-9]/', '', $nip);
    }
}
