<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CutiFactory extends Factory
{
    public function definition(): array
    {
        return [
            'pegawai_id' => null,
            'nomor_surat' => '001/'.fake()->year().'/BAWASLU',
            'jenis_cuti' => fake()->randomElement(['tahunan', 'sakit', 'melahirkan', 'alasan penting']),
            'alasan' => fake()->sentence(),
            'tanggal_mulai' => fake()->date(),
            'tanggal_selesai' => fake()->date(),
            'lama_hari' => fake()->numberBetween(1, 30),
            'keterangan' => fake()->optional()->sentence(),
            'nama_kepala_sekretariat' => fake()->name(),
            'nip_kepala_sekretariat' => fake()->numerify('##########'),
            'nama_sekjen' => fake()->name(),
            'nip_sekjen' => fake()->numerify('##########'),
            'nomor_surat_edaran' => fake()->optional()->numerify('###/'.fake()->year().'/SE'),
        ];
    }
}
