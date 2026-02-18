<?php

namespace App\Services\Pegawai;

use App\Models\Pegawai;
use Exception;
use Rap2hpoutre\FastExcel\Facades\FastExcel;

class ImportIdAbsensiService
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

        $rows = FastExcel::import($filePath);

        foreach ($rows as $index => $row) {
            try {
                $normalizedRow = $this->normalizeRow($row);

                $nip = $normalizedRow['nip'] ?? null;
                $idAbsensi = $normalizedRow['id_absensi'] ?? null;

                // Skip jika NIP kosong
                if (empty($nip)) {
                    $this->result['skipped']++;
                    $this->result['errors'][] = 'Baris '.($index + 2).': NIP kosong, data dilewati';

                    continue;
                }

                // Skip jika ID Absensi kosong
                if (empty($idAbsensi)) {
                    $this->result['skipped']++;
                    $this->result['errors'][] = 'Baris '.($index + 2).': ID Absensi kosong, data dilewati';

                    continue;
                }

                // Cari pegawai berdasarkan NIP (prioritas nip_baru, fallback nip_lama)
                $pegawai = Pegawai::where('nip_baru', $nip)
                    ->orWhere('nip_lama', $nip)
                    ->first();

                if (! $pegawai) {
                    $this->result['skipped']++;
                    $this->result['errors'][] = 'Baris '.($index + 2).": Pegawai dengan NIP {$nip} tidak ditemukan";

                    continue;
                }

                // Cek apakah ID Absensi sudah digunakan oleh pegawai lain
                $existingPegawai = Pegawai::where('id_absensi', $idAbsensi)
                    ->where('id', '!=', $pegawai->id)
                    ->first();

                if ($existingPegawai) {
                    $this->result['skipped']++;
                    $this->result['errors'][] = 'Baris '.($index + 2).": ID Absensi {$idAbsensi} sudah digunakan oleh {$existingPegawai->nama}";

                    continue;
                }

                // Update pegawai dengan ID Absensi
                $pegawai->update(['id_absensi' => $idAbsensi]);
                $this->result['imported']++;
            } catch (Exception $e) {
                $this->result['failed']++;
                $this->result['errors'][] = 'Baris '.($index + 2).': '.$e->getMessage();
            }
        }

        $this->result['success'] = true;
        $this->result['message'] = "Import selesai! Berhasil: {$this->result['imported']}, Dilewati: {$this->result['skipped']}, Gagal: {$this->result['failed']}";

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

    public function getResult(): array
    {
        return $this->result;
    }
}
