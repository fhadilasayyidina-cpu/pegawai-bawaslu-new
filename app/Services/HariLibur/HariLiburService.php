<?php

namespace App\Services\HariLibur;

use App\Models\HariLibur;
use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HariLiburService
{
    /**
     * Create a new holiday.
     *
     * @param  array{date: string, description: string, is_imported?: bool}  $data
     */
    public function create(array $data): HariLibur
    {
        return HariLibur::create([
            'date' => $data['date'],
            'description' => $data['description'],
            'is_imported' => $data['is_imported'] ?? false,
        ]);
    }

    /**
     * Update an existing holiday.
     *
     * @param  array{date: string, description: string, is_imported?: bool}  $data
     */
    public function update(int $id, array $data): HariLibur
    {
        $hariLibur = HariLibur::findOrFail($id);
        $hariLibur->update([
            'date' => $data['date'],
            'description' => $data['description'],
            'is_imported' => $data['is_imported'] ?? $hariLibur->is_imported,
        ]);

        return $hariLibur;
    }

    /**
     * Delete a holiday.
     */
    public function delete(int $id): bool
    {
        $hariLibur = HariLibur::findOrFail($id);

        return (bool) $hariLibur->delete();
    }

    /**
     * Import holiday data for a given year from storage JSON.
     *
     * @throws Exception
     */
    public function importDataLibur(DateTime $tahun): array
    {
        $tahunString = $tahun->format('Y');
        $pathFile = "DataLibur/{$tahunString}.json"; // Hasil: DataLibur/2026.json

        if (! Storage::exists($pathFile)) {
            return [
                'success' => false,
                'message' => "File {$pathFile} tidak ditemukan di storage.",
                'imported' => 0,
                'skipped' => 0,
            ];
        }

        $jsonContent = Storage::get($pathFile);
        $dataLibur = json_decode($jsonContent, true);

        if (is_null($dataLibur)) {
            throw new Exception("Format file JSON {$tahunString}.json rusak/salah.");
        }

        $imported = 0;
        $skipped = 0;

        DB::transaction(function () use ($dataLibur, &$imported, &$skipped) {
            foreach ($dataLibur as $libur) {
                // Check if date already exists
                $existing = HariLibur::where('date', $libur['tanggal'])->first();
                if ($existing) {
                    $existing->update([
                        'description' => $libur['keterangan'],
                        'is_imported' => true,
                    ]);
                    $skipped++;
                } else {
                    HariLibur::create([
                        'date' => $libur['tanggal'],
                        'description' => $libur['keterangan'],
                        'is_imported' => true,
                    ]);
                    $imported++;
                }
            }
        });

        return [
            'success' => true,
            'message' => "Sukses mengimport {$imported} data dan memperbarui/melewati {$skipped} data dari file {$tahunString}.json",
            'imported' => $imported,
            'skipped' => $skipped,
        ];
    }
}
