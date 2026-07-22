<?php

namespace App\Services\Kgb;

use App\Models\SalaryMatrix;
use Exception;
use Rap2hpoutre\FastExcel\Facades\FastExcel;

class ImportSalaryService
{
    public function import(string $filePath): array
    {
        if (! file_exists($filePath)) {
            throw new Exception('File tidak ditemukan.');
        }

        $rows = FastExcel::import($filePath);

        if ($rows->isEmpty()) {
            throw new Exception('File Excel kosong atau tidak dapat dibaca.');
        }

        $firstRow = $rows->first();
        $keys = array_keys((array) $firstRow);

        $imported = 0;
        $created = 0;
        $updated = 0;

        $normalizedKeys = array_map(fn ($k) => strtolower(trim((string) $k)), $keys);
        $isFlat = in_array('golongan', $normalizedKeys) || in_array('gol', $normalizedKeys);

        if ($isFlat) {
            foreach ($rows as $row) {
                $rowArr = [];
                foreach ($row as $k => $v) {
                    $rowArr[strtolower(trim((string) $k))] = $v;
                }

                $jenis = strtoupper((string) ($rowArr['jenis_pegawai'] ?? $rowArr['jenis'] ?? $rowArr['tipe'] ?? 'PNS'));
                if (! in_array($jenis, ['PNS', 'PPPK'])) {
                    $jenis = 'PNS';
                }

                $golongan = (string) ($rowArr['golongan'] ?? $rowArr['gol'] ?? $rowArr['gol_nama'] ?? '');
                $mkg = (int) ($rowArr['mkg_tahun'] ?? $rowArr['mkg'] ?? $rowArr['masa_kerja'] ?? $rowArr['tahun'] ?? 0);
                $gajiRaw = $rowArr['gaji_pokok'] ?? $rowArr['gaji'] ?? $rowArr['nominal'] ?? 0;
                $gaji = $this->cleanNominal($gajiRaw);

                if ($golongan && $gaji > 0) {
                    $record = SalaryMatrix::updateOrCreate(
                        [
                            'jenis_pegawai' => $jenis,
                            'golongan' => trim($golongan),
                            'mkg_tahun' => $mkg,
                        ],
                        [
                            'gaji_pokok' => $gaji,
                        ]
                    );

                    if ($record->wasRecentlyCreated) {
                        $created++;
                    } else {
                        $updated++;
                    }
                    $imported++;
                }
            }
        } else {
            $mkgKey = null;
            foreach ($keys as $k) {
                $lk = strtolower(trim((string) $k));
                if (in_array($lk, ['mkg', 'masa_kerja', 'tahun', 'mk', 'mkg_tahun', '0', 'no'])) {
                    $mkgKey = $k;
                    break;
                }
            }
            if ($mkgKey === null) {
                $mkgKey = $keys[0];
            }

            foreach ($rows as $row) {
                $rowArr = (array) $row;
                $mkg = (int) ($rowArr[$mkgKey] ?? 0);

                foreach ($rowArr as $colName => $val) {
                    if ($colName === $mkgKey) {
                        continue;
                    }

                    $golongan = trim((string) $colName);
                    $gaji = $this->cleanNominal($val);

                    if (! empty($golongan) && $gaji > 0) {
                        $jenis = (preg_match('/^(Golongan\s*\d+|Gol\s*\d+|[IXV]+$)/i', $golongan) && ! preg_match('/^[IVX]+\/[a-e]$/i', $golongan))
                            ? 'PPPK'
                            : 'PNS';

                        $record = SalaryMatrix::updateOrCreate(
                            [
                                'jenis_pegawai' => $jenis,
                                'golongan' => $golongan,
                                'mkg_tahun' => $mkg,
                            ],
                            [
                                'gaji_pokok' => $gaji,
                            ]
                        );

                        if ($record->wasRecentlyCreated) {
                            $created++;
                        } else {
                            $updated++;
                        }
                        $imported++;
                    }
                }
            }
        }

        return [
            'imported' => $imported,
            'created' => $created,
            'updated' => $updated,
        ];
    }

    private function cleanNominal(string|int|float|null $val): int
    {
        if ($val === null) {
            return 0;
        }

        $cleaned = preg_replace('/[^0-9]/', '', (string) $val);

        return (int) $cleaned;
    }
}
