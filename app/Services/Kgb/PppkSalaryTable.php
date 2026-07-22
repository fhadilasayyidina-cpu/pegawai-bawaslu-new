<?php

namespace App\Services\Kgb;

class PppkSalaryTable
{
    /**
     * Data Gaji Pokok PPPK (Perpres No. 11 Tahun 2024).
     * Format: [Golongan => [Tahun => Nominal Gaji]]
     *
     * @var array<string, array<int, int>>
     */
    private array $salaries = [
        'Golongan I' => [
            0 => 1938500,
            2 => 1999500,
            4 => 2062500,
            6 => 2127500,
            8 => 2194500,
            10 => 2263600,
            12 => 2334900,
            14 => 2408400,
            16 => 2484300,
            18 => 2562500,
            20 => 2643200,
            22 => 2726500,
            24 => 2812400,
            26 => 2900900,
        ],
        'Golongan II' => [
            3 => 2116900,
            5 => 2183600,
            7 => 2252400,
            9 => 2323300,
            11 => 2396500,
            13 => 2472000,
            15 => 2549800,
            17 => 2630100,
            19 => 2713000,
            21 => 2798400,
            23 => 2886600,
            25 => 2977500,
            27 => 3071200,
        ],
        'Golongan III' => [
            3 => 2206500,
            5 => 2276000,
            7 => 2347700,
            9 => 2421600,
            11 => 2497900,
            13 => 2576500,
            15 => 2657700,
            17 => 2741400,
            19 => 2827700,
            21 => 2916800,
            23 => 3008700,
            25 => 3103400,
            27 => 3201200,
        ],
        'Golongan IV' => [
            3 => 2299800,
            5 => 2372300,
            7 => 2447000,
            9 => 2524000,
            11 => 2603500,
            13 => 2685500,
            15 => 2770100,
            17 => 2857400,
            19 => 2947400,
            21 => 3040200,
            23 => 3135900,
            25 => 3234700,
            27 => 3336600,
        ],
        'Golongan V' => [
            0 => 2511500,
            1 => 2551000,
            3 => 2631300,
            5 => 2714100,
            7 => 2799600,
            9 => 2887800,
            11 => 2978700,
            13 => 3072500,
            15 => 3169300,
            17 => 3269100,
            19 => 3372000,
            21 => 3478200,
            23 => 3587800,
            25 => 3700800,
            27 => 3817400,
            29 => 3937600,
            31 => 4061600,
            33 => 4189900,
        ],
        'Golongan VI' => [
            3 => 2742800,
            5 => 2829200,
            7 => 2918300,
            9 => 3010200,
            11 => 3105000,
            13 => 3202800,
            15 => 3303700,
            17 => 3407700,
            19 => 3515100,
            21 => 3625800,
            23 => 3740000,
            25 => 3857800,
            27 => 3979300,
            29 => 4104600,
            31 => 4233900,
            33 => 4367100,
        ],
        'Golongan VII' => [
            3 => 2858800,
            5 => 2948800,
            7 => 3041700,
            9 => 3137500,
            11 => 3236300,
            13 => 3338200,
            15 => 3443300,
            17 => 3551700,
            19 => 3663600,
            21 => 3779000,
            23 => 3898000,
            25 => 4020800,
            27 => 4147400,
            29 => 4278000,
            31 => 4412700,
            33 => 4551700,
        ],
        'Golongan VIII' => [
            3 => 2979700,
            5 => 3073500,
            7 => 3170300,
            9 => 3270100,
            11 => 3373100,
            13 => 3479300,
            15 => 3588900,
            17 => 3701900,
            19 => 3818500,
            21 => 3938800,
            23 => 4062800,
            25 => 4190800,
            27 => 4322800,
            29 => 4458900,
            31 => 4599300,
            33 => 4744100,
        ],
        'Golongan IX' => [
            0 => 3203600,
            2 => 3304400,
            4 => 3408500,
            6 => 3515800,
            8 => 3626500,
            10 => 3740700,
            12 => 3858500,
            14 => 3980000,
            16 => 4105300,
            18 => 4234600,
            20 => 4368000,
            22 => 4505500,
            24 => 4647400,
            26 => 4793800,
            28 => 4944800,
            30 => 5100500,
            32 => 5261200,
        ],
        'Golongan X' => [
            0 => 3339100,
            2 => 3444200,
            4 => 3552700,
            6 => 3664600,
            8 => 3780000,
            10 => 3899000,
            12 => 4021800,
            14 => 4148500,
            16 => 4279100,
            18 => 4413900,
            20 => 4553000,
            22 => 4696400,
            24 => 4844300,
            26 => 4996800,
            28 => 5154200,
            30 => 5316600,
            32 => 5484000,
        ],
        'Golongan XI' => [
            0 => 3480300,
            2 => 3589900,
            4 => 3702900,
            6 => 3819500,
            8 => 3939800,
            10 => 4063800,
            12 => 4191800,
            14 => 4323800,
            16 => 4460000,
            18 => 4600500,
            20 => 4745400,
            22 => 4894800,
            24 => 5048900,
            26 => 5207900,
            28 => 5372000,
            30 => 5541200,
            32 => 5716000,
        ],
        'Golongan XII' => [
            0 => 3627500,
            2 => 3741700,
            4 => 3859600,
            6 => 3981100,
            8 => 4106500,
            10 => 4235800,
            12 => 4369200,
            14 => 4506800,
            16 => 4648700,
            18 => 4795100,
            20 => 4946100,
            22 => 5101900,
            24 => 5262600,
            26 => 5428300,
            28 => 5599300,
            30 => 5775700,
            32 => 5957800,
        ],
        'Golongan XIII' => [
            0 => 3781000,
            2 => 3900100,
            4 => 4022900,
            6 => 4149600,
            8 => 4280300,
            10 => 4415100,
            12 => 4554100,
            14 => 4697500,
            16 => 4845500,
            18 => 4998100,
            20 => 5155500,
            22 => 5318000,
            24 => 5485500,
            26 => 5658300,
            28 => 5836500,
            30 => 6020400,
            32 => 6209800,
        ],
        'Golongan XIV' => [
            0 => 3940900,
            2 => 4065000,
            4 => 4193000,
            6 => 4325100,
            8 => 4461300,
            10 => 4601800,
            12 => 4746700,
            14 => 4896200,
            16 => 5050400,
            18 => 5209500,
            20 => 5373600,
            22 => 5542900,
            24 => 5717500,
            26 => 5897600,
            28 => 6083300,
            30 => 6275000,
            32 => 6472600,
        ],
        'Golongan XV' => [
            0 => 4107600,
            2 => 4237000,
            4 => 4370500,
            6 => 4508100,
            8 => 4650100,
            10 => 4796500,
            12 => 4947500,
            14 => 5103300,
            16 => 5264100,
            18 => 5429900,
            20 => 5600900,
            22 => 5777300,
            24 => 5959300,
            26 => 6147000,
            28 => 6340700,
            30 => 6540500,
            32 => 6746500,
        ],
        'Golongan XVI' => [
            0 => 4281400,
            2 => 4416200,
            4 => 4555300,
            6 => 4698700,
            8 => 4846700,
            10 => 4999300,
            12 => 5156700,
            14 => 5319200,
            16 => 5486700,
            18 => 5659500,
            20 => 5837700,
            22 => 6021500,
            24 => 6211200,
            26 => 6406800,
            28 => 6608600,
            30 => 6816700,
            32 => 7031400,
        ],
        'Golongan XVII' => [
            0 => 4462500,
            2 => 4603000,
            4 => 4747900,
            6 => 4897400,
            8 => 5051600,
            10 => 5210700,
            12 => 5374800,
            14 => 5544100,
            16 => 5718700,
            18 => 5898800,
            20 => 6084500,
            22 => 6276200,
            24 => 6473900,
            26 => 6677800,
            28 => 6888100,
            30 => 7105000,
            32 => 7329000,
        ],
    ];

    /**
     * @return array<int, array{id: string, name: string}>
     */
    public function golonganOptions(): array
    {
        return array_map(fn (string $golongan) => [
            'id' => $golongan,
            'name' => $golongan,
        ], array_keys($this->salaries));
    }

    /**
     * List of options for PPPK Jabatan / Golongan dropdown.
     *
     * @return array<int, array{id: string, name: string}>
     */
    public function jabatanGolonganOptions(): array
    {
        $options = [];

        // Pure Golongan options
        foreach (array_keys($this->salaries) as $gol) {
            $options[] = [
                'id' => $gol,
                'name' => $gol,
            ];
        }

        // Common PPPK Jabatan/Golongan combinations
        $commonPositions = [
            'Ahli Pertama - Analis Hukum / Golongan IX',
            'Ahli Pertama - Perencana / Golongan IX',
            'Ahli Pertama - Pranata Komputer / Golongan IX',
            'Ahli Pertama - Pengelola Pengadaan Barang/Jasa / Golongan IX',
            'Ahli Pertama - Analis Kebijakan / Golongan IX',
            'Ahli Pertama - Penata Kelola Pemilu / Golongan IX',
            'Terampil - Arsiparis / Golongan VII',
            'Terampil - Pranata Komputer / Golongan VII',
            'Terampil - Pengelola Pengadaan Barang/Jasa / Golongan VII',
            'Pengemudi / Golongan V',
            'Pramubakti / Golongan I',
            'Petugas Keamanan / Golongan V',
        ];

        foreach ($commonPositions as $pos) {
            $options[] = [
                'id' => $pos,
                'name' => $pos,
            ];
        }

        return $options;
    }

    /**
     * @return array<int, array{id: string, name: string}>
     */
    public function masaKerjaOptions(?string $golongan): array
    {
        $normGol = self::normalizeGolongan($golongan);
        $salaryByMasaKerja = $this->salaries[$normGol] ?? [];

        if (empty($salaryByMasaKerja)) {
            $tahunList = range(0, 33);
        } else {
            $tahunList = array_keys($salaryByMasaKerja);
            sort($tahunList);
        }

        return array_map(fn (int $tahun) => [
            'id' => $this->formatMasaKerja($tahun),
            'name' => $this->formatMasaKerja($tahun),
        ], $tahunList);
    }

    public function salary(?string $golongan, string|int|null $masaKerja): ?int
    {
        $normGol = self::normalizeGolongan($golongan);

        if (! preg_match('/^\s*(\d+)/', (string) $masaKerja, $matches)) {
            return null;
        }

        $tahun = (int) $matches[1];

        $dbSalary = \App\Models\SalaryMatrix::where('jenis_pegawai', 'PPPK')
            ->where(function ($q) use ($golongan, $normGol) {
                $q->where('golongan', $golongan)
                    ->orWhere('golongan', $normGol);
            })
            ->where('mkg_tahun', $tahun)
            ->value('gaji_pokok');

        if ($dbSalary !== null && $dbSalary > 0) {
            return (int) $dbSalary;
        }

        if (! isset($this->salaries[$normGol])) {
            return null;
        }

        if (isset($this->salaries[$normGol][$tahun])) {
            return $this->salaries[$normGol][$tahun];
        }

        $availableYears = array_keys($this->salaries[$normGol]);
        sort($availableYears);

        $matchedYear = null;
        foreach ($availableYears as $y) {
            if ($y <= $tahun) {
                $matchedYear = $y;
            } else {
                break;
            }
        }

        return $matchedYear !== null ? $this->salaries[$normGol][$matchedYear] : null;
    }

    public function formatMasaKerja(int $tahun): string
    {
        return "{$tahun} Tahun 0 Bulan";
    }

    public static function normalizeGolongan(?string $golongan): string
    {
        if (empty($golongan)) {
            return '';
        }

        $str = strtoupper(trim((string) $golongan));

        if (preg_match('/\b(XVII|XVI|XV|XIV|XIII|XII|XI|X|IX|VIII|VII|VI|V|IV|III|II|I)\b/i', $str, $matches)) {
            return 'Golongan '.strtoupper($matches[1]);
        }

        if (preg_match('/\b(1[0-7]|[1-9])\b/', $str, $matches)) {
            $numMap = [
                1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V',
                6 => 'VI', 7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X',
                11 => 'XI', 12 => 'XII', 13 => 'XIII', 14 => 'XIV', 15 => 'XV',
                16 => 'XVI', 17 => 'XVII',
            ];
            $val = (int) $matches[1];
            if (isset($numMap[$val])) {
                return 'Golongan '.$numMap[$val];
            }
        }

        return '';
    }
}
