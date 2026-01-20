<?php

namespace App\Services\Pegawai;

use App\Models\Pegawai;
use Exception;
use Rap2hpoutre\FastExcel\Facades\FastExcel;

class ImportPegawaiService
{
    protected array $result = [
        'imported' => 0,
        'skipped' => 0,
        'failed' => 0,
        'errors' => [],
    ];

    public function import(string $filePath): array
    {
        if (! file_exists($filePath)) {
            throw new Exception('File tidak ditemukan');
        }

        $rows = FastExcel::import($filePath);

        foreach ($rows as $index => $row) {
            try {
                $normalizedRow = $this->normalizeRow($row);
                $pegawaiData = $this->mapRowToPegawai($normalizedRow);

                if (empty($pegawaiData['nip_baru'])) {
                    $this->result['skipped']++;
                    $this->result['errors'][] = 'Baris '.($index + 2).': NIP baru kosong, data dilewati';

                    continue;
                }

                // Skip jika status tidak AKTIF
                if (empty($pegawaiData['status_kepegwaian']) || $pegawaiData['status_kepegwaian'] !== 'AKTIF') {
                    $this->result['skipped']++;
                    $this->result['errors'][] = 'Baris '.($index + 2).': Status tidak AKTIF, data dilewati';

                    continue;
                }

                Pegawai::updateOrCreate(
                    ['nip_baru' => $pegawaiData['nip_baru']],
                    $pegawaiData
                );

                $this->result['imported']++;
            } catch (Exception $e) {
                $this->result['failed']++;
                $this->result['errors'][] = 'Baris '.($index + 2).': '.$e->getMessage();
            }
        }

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

    protected function normalizeJenisKelamin(?string $value): string
    {
        if (empty($value)) {
            return 'Tidak Teridentifikasi';
        }

        $normalized = strtolower(trim($value));

        // Pria variants
        if (in_array($normalized, ['l', 'laki-laki', 'm', 'pria', 'male'])) {
            return 'Pria';
        }

        // Wanita variants
        if (in_array($normalized, ['p', 'perempuan', 'f', 'wanita', 'female'])) {
            return 'Wanita';
        }

        return 'Tidak Teridentifikasi';
    }

    protected function normalizeTingkatPendidikan(?string $value): string
    {
        if (empty($value) || in_array(trim($value), ['-', '.', ''])) {
            return 'Tidak Teridentifikasi';
        }

        $normalized = strtolower(trim($value));

        // Handle compound values like "S1-S2" -> ambil tertinggi
        if (str_contains($normalized, '-s2') || str_contains($normalized, '/s2')) {
            return 'S2';
        }
        if (str_contains($normalized, '-s3') || str_contains($normalized, '/s3')) {
            return 'S3';
        }

        // Clean up suffixes like "DIII Administrasi Bisnis" -> "DIII"
        $normalized = preg_replace('/\s+.+$/', '', $normalized);

        // SD
        if (in_array($normalized, ['sd', 'sekolah dasar'])) {
            return 'SD';
        }

        // SMP
        if (in_array($normalized, ['smp', 'sltp', 'sekolah menengah pertama'])) {
            return 'SMP';
        }

        // SMA/SMK
        if (in_array($normalized, ['sma', 'smk', 'slta', 'smu', 'sekolah menengah atas', 'slta kejuruan'])) {
            return 'SMA/SMK';
        }

        // D1
        if (in_array($normalized, ['d1', 'di', 'd-1', 'diploma 1', 'diploma i'])) {
            return 'D1';
        }

        // D2
        if (in_array($normalized, ['d2', 'dii', 'd-2', 'diploma 2', 'diploma ii'])) {
            return 'D2';
        }

        // D3
        if (in_array($normalized, ['d3', 'd-3', 'diii', 'd-iii', 'diploma 3', 'diploma iii'])) {
            return 'D3';
        }

        // D4
        if (in_array($normalized, ['d4', 'd-4', 'div', 'd-iv', 'diploma 4', 'diploma iv', 'sarjana terapan'])) {
            return 'D4';
        }

        // S1
        if (in_array($normalized, ['s1', 's-1', 's.1', 'sarjana', 'strata 1'])) {
            return 'S1';
        }

        // S2
        if (in_array($normalized, ['s2', 's-2', 's.2', 'magister', 'strata 2'])) {
            return 'S2';
        }

        // S3
        if (in_array($normalized, ['s3', 's-3', 's.3', 'doktor', 'strata 3'])) {
            return 'S3';
        }

        return 'Tidak Teridentifikasi';
    }

    protected function normalizeStatusKepegwaian(?string $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        $normalized = strtoupper(trim($value));

        // Only accept valid status values
        if (in_array($normalized, ['AKTIF', 'TIDAK AKTIF'])) {
            return $normalized;
        }

        return null;
    }

    protected function calculateUsia(\DateTimeInterface|string|null $tgl_lahir): ?int
    {
        if (empty($tgl_lahir)) {
            return null;
        }

        try {
            // Jika sudah DateTime object, gunakan langsung
            if ($tgl_lahir instanceof \DateTimeInterface) {
                $birthDate = \Carbon\Carbon::instance($tgl_lahir);
            } else {
                // Jika string, parse dulu
                $birthDate = \Carbon\Carbon::parse($tgl_lahir);
            }

            $today = \Carbon\Carbon::now();

            return $birthDate->diffInYears($today);
        } catch (\Exception $e) {
            return null;
        }
    }

    protected function calculateRangeUmur(?int $usia): ?string
    {
        if ($usia === null) {
            return null;
        }

        // Range per 10 tahun: 20-29, 30-39, 40-49, dst
        $start = intdiv($usia, 10) * 10;
        $end = $start + 9;

        // Handle usia di bawah 20 tahun
        if ($usia < 20) {
            return '< 20';
        }

        return "{$start}-{$end}";
    }

    protected function validateFile($file): void
    {
        $allowedExtensions = ['xlsx', 'xls', 'csv'];
        $extension = $file->getClientOriginalExtension();

        if (! in_array(strtolower($extension), $allowedExtensions)) {
            throw new Exception('Format file tidak didukung. Gunakan xlsx, xls, atau csv.');
        }
    }

    protected function mapRowToPegawai(array $row): array
    {
        return [
            // Identitas
            'nip_baru' => $row['nip_baru'] ?? null,
            'nip_lama' => $row['nip_lama'] ?? null,
            'nama' => $row['nama'] ?? null,
            'gelar_depan' => $row['gelar_depan'] ?? null,
            'gelar_blk' => $row['gelar_blk'] ?? null,
            'tempat_lahir_nama' => $row['tempat_lahir_nama'] ?? null,
            'jenis_kelamin' => $this->normalizeJenisKelamin($row['jenis_kelamin'] ?? null),
            'gol_darah' => $row['gol_darah'] ?? null,
            'agama_nama' => $row['agama_nama'] ?? null,
            'jenis_kawin_nama' => $row['jenis_kawin_nama'] ?? null,
            'nik' => $row['nik'] ?? null,
            'nomor_hp' => $row['nomor_hp'] ?? null,
            'email' => $row['email'] ?? null,
            'email_gov' => $row['email_gov'] ?? null,
            'alamat' => $row['alamat'] ?? null,
            'tgl_lahir' => $row['tgl_lahir'] ?? null,  // date
            'usia' => $this->calculateUsia($row['tgl_lahir'] ?? null),  // integer

            // Administrasi
            'npwp_nomor' => $row['npwp_nomor'] ?? null,
            'bpjs' => $row['bpjs'] ?? null,
            'kartu_pegawai' => $row['kartu_pegawai'] ?? null,
            'nomor_sk_cpns' => $row['nomor_sk_cpns'] ?? null,
            // 'tgl_sk_cpns' => $row['tgl_sk_cpns'] ?? null,  // date
            // 'tmt_cpns' => $row['tmt_cpns'] ?? null,  // date
            'nomor_sk_pns' => $row['nomor_sk_pns'] ?? null,
            // 'tgl_sk_pns' => $row['tgl_sk_pns'] ?? null,  // date
            // 'tmt_pns' => $row['tmt_pns'] ?? null,  // date
            'no_sk_dpk_penugasan_kontrak' => $row['no_sk_dpk_penugasan_kontrak'] ?? null,
            // 'tgl_sk_dpk_penugasan_kontrak' => $row['tgl_sk_dpk_penugasan_kontrak'] ?? null,  // date
            'keterangan' => $row['keterangan'] ?? null,
            'keterangan_status' => $row['keterangan_status'] ?? null,

            // Golongan & Jabatan
            'gol_awal_nama' => $row['gol_awal_nama'] ?? null,
            'gol_nama' => $row['gol_nama'] ?? null,
            // 'tmt_golongan' => $row['tmt_golongan'] ?? null,  // date
            // 'mkgol' => $row['mkgol'] ?? null,  // integer
            'jenis_jabatan_nama' => $row['jenis_jabatan_nama'] ?? null,
            'jabatan_nama' => $row['jabatan_nama'] ?? null,
            // 'tmt_jabatan' => $row['tmt_jabatan'] ?? null,  // date
            'jabatan_non_definitif' => $row['jabatan_non_definitif'] ?? null,
            'jabatan_non_definitif_1' => $row['jabatan_non_definitif_1'] ?? null,
            // 'mkjab' => $row['mkjab'] ?? null,  // integer
            // 'jumlah' => $row['jumlah'] ?? null,  // integer
            'kelas' => $row['kelas'] ?? null,
            'kelas_jabatan' => $row['kelas_jabatan'] ?? null,
            'kelompok_jabatan' => $row['kelompok_jabatan'] ?? null,
            'nm_kelompok_jabatan' => $row['nm_kelompok_jabatan'] ?? null,
            'nama_kelompok_jabatan' => $row['nama_kelompok_jabatan'] ?? null,
            'pangkat' => $row['pangkat'] ?? null,
            'proyeksi_jf' => $row['proyeksi_jf'] ?? null,

            // Pendidikan
            'tingkat_pendidikan_nama' => $this->normalizeTingkatPendidikan($row['tingkat_pendidikan_nama'] ?? null),
            'pendidikan_nama' => $row['pendidikan_nama'] ?? null,
            'tahun_lulus' => $row['tahun_lulus'] ?? null,
            'riwayat_diklatpim' => $row['riwayat_diklatpim'] ?? null,

            // Unit & Organisasi
            'satuan_kerja' => $row['satuan_kerja'] ?? null,
            'unit_kerja' => $row['unit_kerja'] ?? null,
            'unit_organisasi' => $row['unit_organisasi'] ?? null,
            'unor_nama' => $row['unor_nama'] ?? null,
            'instansi_induk_nama' => $row['instansi_induk_nama'] ?? null,
            'eselon' => $row['eselon'] ?? null,
            'divisi' => $row['divisi'] ?? null,
            'ukm' => $row['ukm'] ?? null,
            'range_umur' => $this->calculateRangeUmur($this->calculateUsia($row['tgl_lahir'] ?? null)),

            // Lokasi
            'provinsi' => $row['provinsi'] ?? null,
            'kab_kota' => $row['kab_kota'] ?? null,

            // Status
            'jenis_pegawai' => $row['jenis_pegawai'] ?? null,
            'status_kepegwaian' => $this->normalizeStatusKepegwaian($row['status_kepegwaian'] ?? null),
        ];
    }

    // protected function parseDate($value): ?string
    // {
    //     if (empty($value)) {
    //         return null;
    //     }

    //     // Handle Excel serial date format
    //     if (is_numeric($value)) {
    //         return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
    //     }

    //     // Handle string date format
    //     if (is_string($value)) {
    //         try {
    //             return date('Y-m-d', strtotime($value));
    //         } catch (Exception $e) {
    //             return null;
    //         }
    //     }

    //     return null;
    // }

    public function getResult(): array
    {
        return $this->result;
    }
}
