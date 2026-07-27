<?php

namespace App\Services\Kgb;

use App\Models\SalaryMatrix;
use Rap2hpoutre\FastExcel\FastExcel;

class ExportSalaryService
{
    /**
     * Export data nominal gaji pokok (PNS & PPPK) ke file Excel
     *
     * @param  string  $filePath  Path file tujuan
     */
    public function export(string $filePath): string
    {
        $dir = dirname($filePath);
        if (! file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        $pnsTable = app(PnsSalaryTable::class);
        $pppkTable = app(PppkSalaryTable::class);

        $data = collect();
        $seenKeys = [];

        // 1. Export standard PNS salaries
        $pnsGolonganList = ['I/a', 'I/b', 'I/c', 'I/d', 'II/a', 'II/b', 'II/c', 'II/d', 'III/a', 'III/b', 'III/c', 'III/d', 'IV/a', 'IV/b', 'IV/c', 'IV/d', 'IV/e'];
        foreach ($pnsGolonganList as $gol) {
            $options = $pnsTable->masaKerjaOptions($gol);
            foreach ($options as $opt) {
                if (preg_match('/^\d+/', $opt['id'], $matches)) {
                    $tahun = (int) $matches[0];
                    $gaji = $pnsTable->salary($gol, $tahun);
                    if ($gaji !== null && $gaji > 0) {
                        $key = "PNS|{$gol}|{$tahun}";
                        $seenKeys[$key] = true;
                        $data->push([
                            'jenis_pegawai' => 'PNS',
                            'golongan' => $gol,
                            'mkg_tahun' => $tahun,
                            'gaji_pokok' => $gaji,
                        ]);
                    }
                }
            }
        }

        // 2. Export standard PPPK salaries
        $pppkGolonganList = $pppkTable->jabatanGolonganOptions();
        foreach ($pppkGolonganList as $golOpt) {
            $gol = $golOpt['id'];
            $options = $pppkTable->masaKerjaOptions($gol);
            foreach ($options as $opt) {
                if (preg_match('/^\d+/', $opt['id'], $matches)) {
                    $tahun = (int) $matches[0];
                    $gaji = $pppkTable->salary($gol, $tahun);
                    if ($gaji !== null && $gaji > 0) {
                        $key = "PPPK|{$gol}|{$tahun}";
                        $seenKeys[$key] = true;
                        $data->push([
                            'jenis_pegawai' => 'PPPK',
                            'golongan' => $gol,
                            'mkg_tahun' => $tahun,
                            'gaji_pokok' => $gaji,
                        ]);
                    }
                }
            }
        }

        // 3. Include any additional records from SalaryMatrix database table
        $dbRecords = SalaryMatrix::all();
        foreach ($dbRecords as $record) {
            $key = "{$record->jenis_pegawai}|{$record->golongan}|{$record->mkg_tahun}";
            if (! isset($seenKeys[$key])) {
                $seenKeys[$key] = true;
                $data->push([
                    'jenis_pegawai' => $record->jenis_pegawai,
                    'golongan' => $record->golongan,
                    'mkg_tahun' => (int) $record->mkg_tahun,
                    'gaji_pokok' => (int) $record->gaji_pokok,
                ]);
            }
        }

        return (new FastExcel($data))->export($filePath);
    }
}
