<?php

namespace App\Services\Absensi;

use App\Models\Absensi;
use App\Models\Pegawai;
use Exception;
use Rap2hpoutre\FastExcel\Facades\FastExcel;

class ImportAbsensiService
{
    protected array $result = [
        'success' => false,
        'message' => '',
        'imported' => 0,
        'skipped' => 0,
        'failed' => 0,
        'errors' => [],
    ];

    public function import(string $filePath, int $createdBy): array
    {
        if (! file_exists($filePath)) {
            throw new Exception('File tidak ditemukan');
        }

        // Gunakan FastExcel dengan PhpSpreadsheet backend
        // Mendukung semua format: xlsx, xls, csv
        $rows = FastExcel::import($filePath);

        foreach ($rows as $index => $row) {
            try {
                $normalizedRow = $this->normalizeRow($row);
                $absensiData = $this->mapRowToAbsensi($normalizedRow, $createdBy);

                if (empty($absensiData['pegawai_id'])) {
                    $this->result['skipped']++;
                    $this->result['errors'][] = 'Baris '.($index + 2).': Pegawai dengan nama "'.$normalizedRow['nama'].'" tidak ditemukan';

                    continue;
                }

                if (empty($absensiData['tanggal'])) {
                    $this->result['skipped']++;
                    $this->result['errors'][] = 'Baris '.($index + 2).': Tanggal tidak valid atau kosong';

                    continue;
                }

                Absensi::updateOrCreate(
                    [
                        'pegawai_id' => $absensiData['pegawai_id'],
                        'tanggal' => $absensiData['tanggal'],
                    ],
                    $absensiData
                );

                $this->result['imported']++;
            } catch (Exception $e) {
                $this->result['failed']++;
                $this->result['errors'][] = 'Baris '.($index + 2).': '.$e->getMessage();
            }
        }

        $this->result['success'] = true;
        $this->result['message'] = "Import selesai. Berhasil: {$this->result['imported']}, Dilewati: {$this->result['skipped']}, Gagal: {$this->result['failed']}";

        return $this->result;
    }

    protected function normalizeRow(array $row): array
    {
        return collect($row)->mapWithKeys(function ($value, $key) {
            $key = strtolower(trim($key));
            $key = str_replace([' ', '.', '/', '-'], '_', $key);
            $key = preg_replace('/_+/', '_', $key);

            return [$key => $value];
        })->toArray();
    }

    protected function parseDateOrNull($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        try {
            // Handle Excel serial date format
            if (is_numeric($value)) {
                $excelEpoch = new \DateTime('1900-01-01');
                $daysToAdd = intval($value) - 2;
                $excelEpoch->add(new \DateInterval("P{$daysToAdd}D"));

                $date = $excelEpoch;

                $year = (int) $date->format('Y');
                if ($year < 1900 || $year > 2100) {
                    return null;
                }

                return $date->format('Y-m-d');
            }

            // Handle string date format - cek DD/MM/YYYY format dulu
            if (is_string($value)) {
                // Cek format DD/MM/YYYY terlebih dahulu
                if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $value, $matches)) {
                    $day = $matches[1];
                    $month = $matches[2];
                    $year = $matches[3];

                    // Validasi tahun
                    if ($year < 1900 || $year > 2100) {
                        return null;
                    }

                    return sprintf('%04d-%02d-%02d', $year, $month, $day);
                }

                // Parse dengan Carbon untuk format lain
                $date = \Carbon\Carbon::parse($value);

                $year = $date->year;
                if ($year < 1900 || $year > 2100) {
                    return null;
                }

                return $date->format('Y-m-d');
            }

            if ($value instanceof \DateTimeInterface) {
                return $value->format('Y-m-d');
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    protected function findPegawaiByName(?string $nama): ?int
    {
        if (empty($nama)) {
            return null;
        }

        $pegawai = Pegawai::where('nama', 'like', '%'.trim($nama).'%')->first();

        return $pegawai?->id;
    }

    protected function parseTimeOrNull($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        try {
            // Handle string time format (HH:MM or HH:MM:SS)
            if (is_string($value)) {
                // Cek format HH:MM atau HH:MM:SS
                if (preg_match('/^(\d{1,2}):(\d{2})(:(\d{2}))?$/', $value, $matches)) {
                    $hour = (int) $matches[1];
                    $minute = (int) $matches[2];

                    // Validasi jam dan menit
                    if ($hour < 0 || $hour > 23 || $minute < 0 || $minute > 59) {
                        return null;
                    }

                    return sprintf('%02d:%02d', $hour, $minute);
                }

                // Coba parse dengan Carbon untuk format lain
                $time = \Carbon\Carbon::parse($value);

                return $time->format('H:i');
            }

            // Handle DateTime object
            if ($value instanceof \DateTimeInterface) {
                return $value->format('H:i');
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    protected function mapRowToAbsensi(array $row, int $createdBy): array
    {
        $pegawaiId = $this->findPegawaiByName($row['nama'] ?? null);
        $tanggal = $this->parseDateOrNull($row['tanggal'] ?? null);
        $scanMasuk = $this->parseTimeOrNull($row['scan_masuk'] ?? $row['scanmasuk'] ?? null);
        $scanPulang = $this->parseTimeOrNull($row['scan_pulang'] ?? $row['scanpulang'] ?? null);

        // Aturan: jika ada scan_masuk ATAU scan_pulang, status otomatis Hadir, default Tidak Hadir
        $status = (! empty($scanMasuk) || ! empty($scanPulang)) ? 'Hadir' : 'Tidak Hadir';

        // Keterangan berisi info scan masuk/pulang (format HH:MM - HH:MM)
        $keterangan = null;
        if ($scanMasuk || $scanPulang) {
            $keterangan = ($scanMasuk ?? '-').' - '.($scanPulang ?? '-');
        }

        return [
            'pegawai_id' => $pegawaiId,
            'tanggal' => $tanggal,
            'status' => $status,
            'keterangan' => $keterangan,
            'created_by' => $createdBy,
        ];
    }

    public function getResult(): array
    {
        return $this->result;
    }
}
