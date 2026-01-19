<?php

namespace App\Services\Pegawai;

use App\Models\Pegawai;
use Exception;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\Facades\FastExcel;

class ImportPegawaiService
{
    protected array $result = [
        'imported' => 0,
        'skipped' => 0,
        'failed' => 0,
        'errors' => [],
    ];

    public function import($file): array
    {
        // Validate file
        $this->validateFile($file);

        try {
            DB::beginTransaction();

            $collection = FastExcel::import($file);

            $imported = 0;
            $skipped = 0;
            $errors = [];

            foreach ($collection as $row) {
                try {
                    // Map Excel columns to database fields
                    $data = $this->mapRowToPegawai($row);

                    // Check if pegawai already exists (by NIP)
                    $existing = null;
                    if (! empty($data['nip_baru'])) {
                        $existing = Pegawai::where('nip_baru', $data['nip_baru'])->first();
                    } elseif (! empty($data['nik'])) {
                        $existing = Pegawai::where('nik', $data['nik'])->first();
                    }

                    if ($existing) {
                        // Update existing
                        $existing->update($data);
                        $skipped++;
                    } else {
                        // Create new
                        Pegawai::create($data);
                        $imported++;
                    }
                } catch (Exception $e) {
                    $errors[] = 'Row error: '.$e->getMessage();
                    $this->result['failed']++;
                }
            }

            DB::commit();

            return [
                'success' => true,
                'imported' => $imported,
                'skipped' => $skipped,
                'failed' => $this->result['failed'],
                'errors' => $errors,
                'message' => "Berhasil mengimport {$imported} data pegawai".($skipped > 0 ? ", {$skipped} data di-skip" : '').($this->result['failed'] > 0 ? ", {$this->result['failed']} gagal" : ''),
            ];
        } catch (Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'imported' => 0,
                'skipped' => 0,
                'failed' => 0,
                'errors' => [$e->getMessage()],
                'message' => 'Import gagal: '.$e->getMessage(),
            ];
        }
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
        // Map Excel column headers to database fields
        // Adjust mapping based on your actual Excel file structure
        return [
            'nip_baru' => $row['nip_baru'] ?? $row['NIP Baru'] ?? $row['nip'] ?? null,
            'nip_lama' => $row['nip_lama'] ?? $row['NIP Lama'] ?? null,
            'nama' => $row['nama'] ?? $row['Nama'] ?? $row['NAMA'] ?? null,
            'gelar_depan' => $row['gelar_depan'] ?? $row['Gelar Depan'] ?? null,
            'gelar_blk' => $row['gelar_blk'] ?? $row['Gelar Belakang'] ?? null,
            'keterangan' => $row['keterangan'] ?? $row['Keterangan'] ?? null,
            'tempat_lahir_nama' => $row['tempat_lahir_nama'] ?? $row['Tempat Lahir'] ?? null,
            'tgl_lahir' => $this->parseDate($row['tgl_lahir'] ?? $row['Tanggal Lahir'] ?? null),
            'jenis_kelamin' => $row['jenis_kelamin'] ?? $row['Jenis Kelamin'] ?? null,
            'agama_nama' => $row['agama_nama'] ?? $row['Agama'] ?? null,
            'jenis_kawin_nama' => $row['jenis_kawin_nama'] ?? $row['Status Kawin'] ?? null,
            'nik' => $row['nik'] ?? $row['NIK'] ?? null,
            'nomor_hp' => $row['nomor_hp'] ?? $row['No HP'] ?? $row['Telepon'] ?? null,
            'email' => $row['email'] ?? $row['Email'] ?? null,
            'email_gov' => $row['email_gov'] ?? $row['Email Gov'] ?? null,
            'alamat' => $row['alamat'] ?? $row['Alamat'] ?? null,
            'npwp_nomor' => $row['npwp_nomor'] ?? $row['NPWP'] ?? null,
            'bpjs' => $row['bpjs'] ?? $row['No BPJS'] ?? null,
            'kartu_pegawai' => $row['kartu_pegawai'] ?? $row['No Kartu Pegawai'] ?? null,
            'nomor_sk_cpns' => $row['nomor_sk_cpns'] ?? $row['No SK CPNS'] ?? null,
            'tgl_sk_cpns' => $this->parseDate($row['tgl_sk_cpns'] ?? $row['Tanggal SK CPNS'] ?? null),
            'tmt_cpns' => $this->parseDate($row['tmt_cpns'] ?? $row['TMT CPNS'] ?? null),
            'nomor_sk_pns' => $row['nomor_sk_pns'] ?? $row['No SK PNS'] ?? null,
            'tgl_sk_pns' => $this->parseDate($row['tgl_sk_pns'] ?? $row['Tanggal SK PNS'] ?? null),
            'tmt_pns' => $this->parseDate($row['tmt_pns'] ?? $row['TMT PNS'] ?? null),
            'gol_awal_nama' => $row['gol_awal_nama'] ?? $row['Golongan Awal'] ?? null,
            'gol_nama' => $row['gol_nama'] ?? $row['Golongan'] ?? null,
            'tmt_golongan' => $this->parseDate($row['tmt_golongan'] ?? $row['TMT Golongan'] ?? null),
            'jenis_jabatan_nama' => $row['jenis_jabatan_nama'] ?? $row['Jenis Jabatan'] ?? null,
            'jabatan_nama' => $row['jabatan_nama'] ?? $row['Jabatan'] ?? null,
            'tmt_jabatan' => $this->parseDate($row['tmt_jabatan'] ?? $row['TMT Jabatan'] ?? null),
            'unor_nama' => $row['unor_nama'] ?? $row['Unit Organisasi'] ?? null,
            'instansi_induk_nama' => $row['instansi_induk_nama'] ?? $row['Instansi Induk'] ?? null,
            'eselon' => $row['eselon'] ?? $row['Eselon'] ?? null,
            'kelompok_jabatan' => $row['kelompok_jabatan'] ?? null,
            'nm_kelompok_jabatan' => $row['nm_kelompok_jabatan'] ?? $row['Kelompok Jabatan'] ?? null,
            'range_umur' => $row['range_umur'] ?? $row['Range Umur'] ?? null,
            'kelas_jabatan' => $row['kelas_jabatan'] ?? $row['Kelas Jabatan'] ?? null,
            'keterangan_status' => $row['keterangan_status'] ?? $row['Status'] ?? null,
            'tingkat_pendidikan_nama' => $row['tingkat_pendidikan_nama'] ?? $row['Pendidikan'] ?? null,
            'satuan_kerja' => $row['satuan_kerja'] ?? $row['Satuan Kerja'] ?? null,
            'provinsi' => $row['provinsi'] ?? $row['Provinsi'] ?? null,
            'kab_kota' => $row['kab_kota'] ?? $row['Kabupaten/Kota'] ?? null,
            'jenis_pegawai' => $row['jenis_pegawai'] ?? $row['Jenis Pegawai'] ?? null,
            'status_kepegwaian' => $row['status_kepegwaian'] ?? $row['Status Kepegawaian'] ?? null,
        ];
    }

    protected function parseDate($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        // Handle Excel serial date format
        if (is_numeric($value)) {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
        }

        // Handle string date format
        if (is_string($value)) {
            try {
                return date('Y-m-d', strtotime($value));
            } catch (Exception $e) {
                return null;
            }
        }

        return null;
    }

    public function getResult(): array
    {
        return $this->result;
    }
}
