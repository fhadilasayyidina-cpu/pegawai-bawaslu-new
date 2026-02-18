<?php

namespace App\Services;

use App\Models\HariLibur;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ImportHariLiburService
{
    protected array $result = [
        'imported' => 0,
        'skipped' => 0,
        'failed' => 0,
        'errors' => [],
    ];

    public function importFromApi(): array
    {
        try {
            $response = Http::timeout(30)->get('https://libur.deno.dev/api');

            if (! $response->successful()) {
                $this->result['errors'][] = 'Gagal mengambil data dari API: '.$response->status();

                return $this->result;
            }

            $holidays = $response->json();

            if (! is_array($holidays)) {
                $this->result['errors'][] = 'Format data API tidak valid.';

                return $this->result;
            }

            DB::beginTransaction();

            foreach ($holidays as $item) {
                try {
                    if (! isset($item['date']) || ! isset($item['name'])) {
                        $this->result['failed']++;
                        $this->result['errors'][] = 'Data tidak lengkap: '.json_encode($item);

                        continue;
                    }

                    $existing = HariLibur::where('date', $item['date'])->first();

                    if ($existing) {
                        if ($existing->is_imported) {
                            // Update data yang sudah ada sebelumnya dari import
                            $existing->update([
                                'description' => $item['name'],
                            ]);
                            $this->result['skipped']++;
                        } else {
                            // Lewati jika data manual
                            $this->result['skipped']++;
                        }
                    } else {
                        // Create baru
                        HariLibur::create([
                            'date' => $item['date'],
                            'description' => $item['name'],
                            'is_imported' => true,
                        ]);
                        $this->result['imported']++;
                    }
                } catch (\Exception $e) {
                    $this->result['failed']++;
                    $this->result['errors'][] = 'Error processing date '.($item['date'] ?? 'unknown').': '.$e->getMessage();
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->result['errors'][] = 'Exception: '.$e->getMessage();
        }

        return $this->result;
    }
}
