<?php

namespace App\Services\Pegawai;

use App\Models\Pegawai;
use Exception;
use Rap2hpoutre\FastExcel\Facades\FastExcel;

class ImportPegawaiService
{
    protected array $result = [
        'imported' => 0,
        'created' => 0,
        'updated' => 0,
        'skipped' => 0,
        'failed' => 0,
        'errors' => [],
    ];

    public function import(string $filePath): array
    {
        if (! file_exists($filePath)) {
            throw new Exception('File tidak ditemukan');
        }

        $this->result = [
            'imported' => 0,
            'created' => 0,
            'updated' => 0,
            'skipped' => 0,
            'failed' => 0,
            'errors' => [],
        ];

        $rows = FastExcel::import($filePath);

        foreach ($rows as $index => $row) {
            try {
                $normalizedRow = $this->normalizeRow($row);
                $pegawaiData = $this->mapRowToPegawai($normalizedRow);

                if (empty($pegawaiData['nip_baru']) || empty($pegawaiData['nama'])) {
                    $this->result['skipped']++;
                    $this->result['errors'][] = 'Baris '.($index + 2).': NIP atau nama kosong, data dilewati';

                    continue;
                }

                // Status bersifat opsional agar file data sederhana (NIP dan nama) tetap dapat diimpor.
                if (! empty($pegawaiData['status_kepegwaian']) && $pegawaiData['status_kepegwaian'] !== 'AKTIF') {
                    $this->result['skipped']++;
                    $this->result['errors'][] = 'Baris '.($index + 2).': Status tidak AKTIF, data dilewati';

                    continue;
                }

                $pegawai = Pegawai::updateOrCreate(
                    ['nip_baru' => $pegawaiData['nip_baru']],
                    $pegawaiData
                );

                $this->result['imported']++;
                $pegawai->wasRecentlyCreated ? $this->result['created']++ : $this->result['updated']++;
            } catch (Exception $e) {
                $this->result['failed']++;
                $this->result['errors'][] = 'Baris '.($index + 2).': '.$e->getMessage();
            }
        }

        return $this->result;
    }

    protected function normalizeRow(array $row): array
    {
        $normalized = collect($row)->mapWithKeys(function ($value, $key) {
            $key = strtolower(trim($key));
            $key = str_replace([' ', '.', '/', '-'], '_', $key);
            $key = preg_replace('/_+/', '_', $key);

            return [$key => $value];
        })->toArray();

        // Terima variasi header yang umum dipakai pada file kepegawaian.
        $aliases = [
            'nip' => 'nip_baru',
            'nip_baru_' => 'nip_baru',
            'nama_pegawai' => 'nama',
            'nama_lengkap' => 'nama',
            'jenis_kelamin_pegawai' => 'jenis_kelamin',
            'agama' => 'agama_nama',
            'jabatan' => 'jabatan_nama',
            'jenis_jabatan' => 'jenis_jabatan_nama',
            'pendidikan' => 'pendidikan_nama',
            'tingkat_pendidikan' => 'tingkat_pendidikan_nama',
            'kabupaten_kota' => 'kab_kota',
            'kabupaten_kota_' => 'kab_kota',
            'status_kepegawaian' => 'status_kepegwaian',
        ];

        foreach ($aliases as $from => $to) {
            if (array_key_exists($from, $normalized) && ! array_key_exists($to, $normalized)) {
                $normalized[$to] = $normalized[$from];
            }
        }

        return $normalized;
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
            'nip_baru' => $this->normalizeNip($row['nip_baru'] ?? null),
            'nip_lama' => $row['nip_lama'] ?? null,
            'nama' => isset($row['nama']) ? (
                (!empty($row['gelar_blk']) && $row['gelar_blk'] !== '-' && !str_contains($row['nama'], $row['gelar_blk'])) 
                    ? ($row['nama'] . ', ' . $row['gelar_blk']) 
                    : $row['nama']
            ) : null,
            'gelar_depan' => $row['gelar_depan'] ?? null,
            'gelar_blk' => $row['gelar_blk'] ?? null,
            'tempat_lahir_nama' => $row['tempat_lahir_nama'] ?? null,
            'jenis_kelamin' => $this->normalizeJenisKelamin($row['jenis_kelamin'] ?? null),
            'gol_darah' => $this->normalizeGolDarah($row['gol_darah'] ?? null),
            'agama_nama' => $row['agama_nama'] ?? null,
            'jenis_kawin_nama' => $row['jenis_kawin_nama'] ?? null,
            'nik' => $row['nik'] ?? null,
            'nomor_hp' => $row['nomor_hp'] ?? null,
            'email' => $row['email'] ?? null,
            'email_gov' => $row['email_gov'] ?? null,
            'alamat' => $row['alamat'] ?? null,
            'tgl_lahir' => $this->parseDateOrNull($row['tgl_lahir'] ?? null),
            'usia' => $this->calculateUsia($this->parseDateOrNull($row['tgl_lahir'] ?? null)),

            // Administrasi
            'npwp_nomor' => $row['npwp_nomor'] ?? null,
            'bpjs' => $row['bpjs'] ?? null,
            'kartu_pegawai' => $row['kartu_pegawai'] ?? null,
            'nomor_sk_cpns' => $row['nomor_sk_cpns'] ?? null,
            'tgl_sk_cpns' => $this->parseDateOrNull($row['tgl_sk_cpns'] ?? null),
            'tmt_cpns' => $this->parseDateOrNull($row['tmt_cpns'] ?? null),
            'nomor_sk_pns' => $row['nomor_sk_pns'] ?? null,
            'tgl_sk_pns' => $this->parseDateOrNull($row['tgl_sk_pns'] ?? null),
            'tmt_pns' => $this->parseDateOrNull($row['tmt_pns'] ?? null),
            'no_sk_dpk_penugasan_kontrak' => $row['no_sk_dpk_penugasan_kontrak'] ?? null,
            'tgl_sk_dpk_penugasan_kontrak' => $this->parseDateOrNull($row['tgl. sk dpk/penugasan/kontrak'] ?? null),
            'keterangan' => $row['keterangan'] ?? null,
            'keterangan_status' => $row['keterangan_status'] ?? null,

            // Golongan & Jabatan
            'gol_awal_nama' => $row['gol_awal_nama'] ?? null,
            'gol_nama' => $row['gol_nama'] ?? null,
            'tmt_golongan' => $this->parseDateOrNull($row['tmt_golongan'] ?? null),
            'mkgol' => $this->parseIntegerOrNull($row['mkgol'] ?? null),
            'jenis_jabatan_nama' => $row['jenis_jabatan_nama'] ?? null,
            'jabatan_nama' => $row['jabatan_nama'] ?? null,
            'tmt_jabatan' => $this->parseDateOrNull($row['tmt_jabatan'] ?? null),
            'jabatan_non_definitif' => $row['jabatan_non_definitif'] ?? null,
            'jabatan_non_definitif_1' => $row['jabatan_non_definitif_1'] ?? null,
            'mkjab' => $this->parseIntegerOrNull($row['mkjab'] ?? null),
            'jumlah' => $this->parseIntegerOrNull($row['jumlah'] ?? null),
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
            'tahun_lulus' => $this->parseIntegerOrNull($row['tahun_lulus'] ?? null),
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
            'range_umur' => $this->calculateRangeUmur($this->calculateUsia($this->parseDateOrNull($row['tgl_lahir'] ?? null))),

            // Lokasi
            'provinsi' => $row['provinsi'] ?? null,
            'kab_kota' => $row['kab_kota'] ?? null,

            // Status
            'jenis_pegawai' => $row['jenis_pegawai'] ?? null,
            'status_kepegwaian' => $this->normalizeStatusKepegwaian($row['status_kepegwaian'] ?? null),
        ];
    }

    protected function normalizeNip(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        // Excel kerap mengirim NIP sebagai angka atau notasi ilmiah.
        if (is_numeric($value)) {
            return number_format((float) $value, 0, '', '');
        }

        return trim((string) $value);
    }

    protected function normalizeGolDarah(mixed $value): ?string
    {
        $value = strtoupper(trim((string) $value));

        return in_array($value, ['A', 'B', 'AB', 'O', '-', 'TIDAK TAHU'], true) ? $value : null;
    }

    protected function parseDateOrNull($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        try {
            // Handle Excel serial date format (OpenSpout/Spout format)
            if (is_numeric($value)) {
                // Excel base date: 1 = January 1, 1900 (Excel 1900 date system)
                // OpenSpout returns the raw Excel serial number
                $excelEpoch = new \DateTime('1900-01-01');
                $daysToAdd = intval($value) - 2; // -2 because Excel treats 1900 as a leap year (bug) and 1-based index
                $excelEpoch->add(new \DateInterval("P{$daysToAdd}D"));

                $date = $excelEpoch;

                // Validasi: pastikan tahun reasonable (1900-2100)
                $year = (int) $date->format('Y');
                if ($year < 1900 || $year > 2100) {
                    return null;
                }

                return $date->format('Y-m-d');
            }

            // Handle string date format
            if (is_string($value)) {
                // Cek apakah string bisa di-parse menjadi tanggal valid
                $date = \Carbon\Carbon::parse($value);

                // Validasi: pastikan tahun reasonable (1900-2100)
                $year = $date->year;
                if ($year < 1900 || $year > 2100) {
                    return null;
                }

                return $date->format('Y-m-d');
            }

            // Handle DateTime object
            if ($value instanceof \DateTimeInterface) {
                return $value->format('Y-m-d');
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    protected function parseIntegerOrNull($value): ?int
    {
        if (empty($value) && $value !== 0 && $value !== '0') {
            return null;
        }

        // Jika sudah integer, return langsung
        if (is_int($value)) {
            return $value;
        }

        // Cek apakah numeric
        if (is_numeric($value)) {
            $intValue = (int) $value;

            // Validasi: pastikan nilai reasonable (0-150 untuk usia/tahun, 0-100 untuk mk)
            if ($intValue < 0 || $intValue > 999) {
                return null;
            }

            return $intValue;
        }

        return null;
    }

    public function getResult(): array
    {
        return $this->result;
    }
}
