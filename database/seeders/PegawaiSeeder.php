<?php

namespace Database\Seeders;

use App\Models\Pegawai;
use App\Services\Pegawai\ImportPegawaiService;
use Illuminate\Database\Seeder;

class PegawaiSeeder extends Seeder
{
    public function run(): void
    {
        Pegawai::truncate();

        $path = database_path('seeders/data/pegawai.xlsx');

        if (file_exists($path)) {
            $service = new ImportPegawaiService();
            $service->import($path);
        }
    }
}
