<?php

namespace App\Services\Kgb;

use App\Models\Pegawai;
use Carbon\Carbon;
use Exception;

class ImportKgbService
{
    protected array $result = [
        'success' => false,
        'message' => '',
        'imported' => 0,
        'skipped' => 0,
        'failed' => 0,
        'errors' => [],
    ];

    /**
     * Import KGB data dari Excel/CSV
     *
     * @param  string  $filePath  Path ke file yang akan diimport
     */
    public function import(string $filePath): array
    {
        if (! file_exists($filePath)) {
            throw new Exception('File tidak ditemukan');
        }

        $rows = \Rap2hpoutre\FastExcel\Facades\FastExcel::import($filePath);

        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // +2 karena header baris 1, index mulai 0

            try {
                $this->processRow($row, $rowNumber);
            } catch (Exception $e) {
                $this->result['failed']++;
                $this->result['errors'][] = "Baris {$rowNumber}: {$e->getMessage()}";
            }
        }

        $this->result['success'] = true;
        $this->result['message'] = "Import selesai. Berhasil: {$this->result['imported']}, Dilewati: {$this->result['skipped']}, Gagal: {$this->result['failed']}";

        return $this->result;
    }

    protected function processRow(array $row, int $rowNumber): void
    {
        // Normalize column names
        $normalized = $this->normalizeRow($row);

        // Validate required fields
        $nip = $normalized['nip'] ?? $normalized['nip_baru'] ?? $normalized['nip_lama'] ?? null;

        if (empty($nip)) {
            throw new Exception('Kolom NIP tidak ditemukan');
        }

        if (empty($normalized['tgl_kgb_terakhir'])) {
            throw new Exception('Kolom tgl_kgb_terakhir tidak ditemukan atau kosong');
        }

        // Find pegawai by NIP (try nip_baru first, then nip_lama)
        $pegawai = Pegawai::where('nip_baru', $nip)
            ->orWhere('nip_lama', $nip)
            ->first();

        if (! $pegawai) {
            $this->result['skipped']++;
            $this->result['errors'][] = "Baris {$rowNumber}: Pegawai dengan NIP {$nip} tidak ditemukan";

            return;
        }

        // Parse and validate date
        $kgbDate = $this->parseDate($normalized['tgl_kgb_terakhir']);

        if (! $kgbDate) {
            throw new Exception('Format tanggal tgl_kgb_terakhir tidak valid');
        }

        // Update pegawai
        $pegawai->update([
            'tgl_kgb_terakhir' => $kgbDate,
        ]);

        $this->result['imported']++;
    }

    protected function normalizeRow(array $row): array
    {
        $normalized = [];

        foreach ($row as $key => $value) {
            // Normalize key: lowercase, replace spaces/dots with underscores
            $normalizedKey = strtolower(trim((string) $key));
            $normalizedKey = preg_replace('/[\s.]+/', '_', $normalizedKey);
            $normalizedKey = preg_replace('/_+/', '_', $normalizedKey);

            $normalized[$normalizedKey] = $value;
        }

        return $normalized;
    }

    protected function parseDate($date): ?Carbon
    {
        if (empty($date)) {
            return null;
        }

        // Handle Excel serial date
        if (is_numeric($date) && $date > 0) {
            $excelEpoch = new \DateTime('1900-01-01');
            $daysToAdd = intval($date) - 2;
            $excelEpoch->add(new \DateInterval("P{$daysToAdd}D"));

            $year = (int) $excelEpoch->format('Y');
            if ($year < 1900 || $year > 2100) {
                return null;
            }

            return Carbon::parse($excelEpoch->format('Y-m-d'))->startOfDay();
        }

        // Try various date formats
        $formats = [
            'd/m/Y',
            'd-m-Y',
            'Y-m-d',
            'd/m/y',
            'd-M-y',
            'd M Y',
        ];

        foreach ($formats as $format) {
            try {
                $parsed = Carbon::createFromFormat($format, $date);
                $year = $parsed->year;

                if ($year >= 1900 && $year <= 2100) {
                    return $parsed->startOfDay();
                }
            } catch (Exception $e) {
                continue;
            }
        }

        // Try Carbon::parse as last resort
        try {
            $parsed = Carbon::parse($date);
            $year = $parsed->year;

            if ($year >= 1900 && $year <= 2100) {
                return $parsed->startOfDay();
            }
        } catch (Exception $e) {
            // Ignore
        }

        return null;
    }
}
