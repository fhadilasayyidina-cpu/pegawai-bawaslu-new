<?php

namespace App\Livewire\Admin;

use App\Models\Pegawai;
use App\Services\Pegawai\PegawaiService;
use App\Services\Statistic\PegawaiStatisticService;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;
use Asantibanez\LivewireCharts\Models\PieChartModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public ?string $kabKota = null;

    public array $kabKotaOptions = [];

    // public array $statistics = [];

    private array $colors = [
        '#a6192e',
        '#e5ad25',
        '#7b1822',
        '#f4c542',
        '#cf4b58',
        '#c58a12',
        '#5b1119',
    ];

    public function mount(): void
    {
        $kabKotaList = Cache::rememberForever('dashboard-kabkota-options', function () {
            return app(PegawaiService::class)->getKabKota()->toArray();
        });

        array_unshift($kabKotaList, (object) [
            'id' => '',
            'name' => 'Semua Kabupaten/Kota',
        ]);

        $this->kabKotaOptions = $kabKotaList;

        $this->loadStatistics();
    }

    public function updatedKabKota(): void
    {
        $this->loadStatistics();
    }

    private function loadStatistics(): void
    {
        $this->statistics = app(PegawaiStatisticService::class)
            ->getAllStats($this->kabKota);
    }

    private function buildColumnChart(
        string $title,
        array $chartData,
        bool $horizontal = false,
        bool $allowFallbackLabel = false
    ): ColumnChartModel {
        $chart = (new ColumnChartModel)
            ->setTitle($title)
            ->setAnimated(true)
            ->withDataLabels()
            ->withoutLegend()
            ->setColumnWidth($horizontal ? 58 : 62)
            ->setJsonConfig([
                'chart.fontFamily' => "'Inter, ui-sans-serif, system-ui, sans-serif'",
                'yaxis.title.text' => "''",
                'xaxis.labels.style.fontSize' => "'12px'",
                'yaxis.labels.style.fontSize' => "'12px'",
                'dataLabels.style.fontSize' => "'12px'",
                'dataLabels.style.fontWeight' => '600',
            ]);

        if ($horizontal) {
            $chart->setHorizontal();
        }

        foreach ($chartData['labels'] ?? [] as $index => $label) {
            $displayLabel = $allowFallbackLabel
                ? ($label ?: 'Tidak Teridentifikasi')
                : $label;

            $chart->addColumn(
                $displayLabel,
                $chartData['values'][$index] ?? 0,
                $this->colors[$index % count($this->colors)]
            );
        }

        return $chart;
    }

    private function buildPieChart(
        string $title,
        array $chartData,
        bool $compactLegend = false,
        bool $allowFallbackLabel = false
    ): PieChartModel {
        $config = [
            'chart.fontFamily' => "'Inter, ui-sans-serif, system-ui, sans-serif'",
            'title.style.fontSize' => "'16px'",
            'dataLabels.style.fontSize' => "'12px'",
            'dataLabels.style.fontWeight' => '600',
            'legend.fontSize' => $compactLegend ? "'10px'" : "'12px'",
            'legend.itemMargin.vertical' => $compactLegend ? '1' : '4',
        ];

        if ($compactLegend) {
            $config['plotOptions.pie.customScale'] = '1.12';
        }

        $chart = (new PieChartModel)
            ->setTitle($title)
            ->setAnimated(true)
            ->withDataLabels()
            ->legendPositionBottom()
            ->legendHorizontallyAlignedCenter()
            ->setJsonConfig($config);

        foreach ($chartData['labels'] ?? [] as $index => $label) {
            $displayLabel = $allowFallbackLabel
                ? ($label ?: 'Tidak Teridentifikasi')
                : $label;

            $chart->addSlice(
                $displayLabel,
                $chartData['values'][$index] ?? 0,
                $this->colors[$index % count($this->colors)]
            );
        }

        return $chart;
    }

    // =======================
    // CHART GETTERS
    // =======================

    // private function getJenisKelaminColumnChart(): ColumnChartModel
    // {
    //     return $this->buildColumnChart(
    //         'Distribusi Jenis Kelamin',
    //         $this->statistics['jenis_kelamin_chart'] ?? []
    //     );
    // }

    // private function getJenisKelaminPieChart(): PieChartModel
    // {
    //     return $this->buildPieChart(
    //         'Distribusi Jenis Kelamin',
    //         $this->statistics['jenis_kelamin_chart'] ?? []
    //     );
    // }

    // private function getPendidikanColumnChart(): ColumnChartModel
    // {
    //     return $this->buildColumnChart(
    //         'Tingkat Pendidikan',
    //         $this->statistics['pendidikan_chart'] ?? []
    //     );
    // }

    // private function getPendidikanPieChart(): PieChartModel
    // {
    //     return $this->buildPieChart(
    //         'Tingkat Pendidikan',
    //         $this->statistics['pendidikan_chart'] ?? []
    //     );
    // }

    // private function getJenisJabatanColumnChart(): ColumnChartModel
    // {
    //     return $this->buildColumnChart(
    //         'Distribusi Jenis Jabatan',
    //         $this->statistics['jenis_jabatan_chart'] ?? [],
    //         true,
    //         true
    //     );
    // }

    // private function getJenisJabatanPieChart(): PieChartModel
    // {
    //     return $this->buildPieChart(
    //         'Persentase Jenis Jabatan',
    //         $this->statistics['jenis_jabatan_chart'] ?? [],
    //         true,
    //         true
    //     );
    // }

    // private function getRangeUmurColumnChart(): ColumnChartModel
    // {
    //     return $this->buildColumnChart(
    //         'Distribusi Range Umur',
    //         $this->statistics['range_umur_chart'] ?? [],
    //         false,
    //         true
    //     );
    // }

    // private function getRangeUmurPieChart(): PieChartModel
    // {
    //     return $this->buildPieChart(
    //         'Persentase Range Umur',
    //         $this->statistics['range_umur_chart'] ?? [],
    //         false,
    //         true
    //     );
    // }

    public function getBirthdayEmployeesProperty()
    {
        $today = Carbon::today();

        return Cache::remember(
            'dashboard-birthday-' . $today->toDateString(),
            now()->addHours(12),
            function () use ($today) {
                $query = Pegawai::query()
                    ->whereNotNull('tgl_lahir');

                if (DB::getDriverName() === 'sqlite') {
                    $query->whereRaw("strftime('%m', tgl_lahir) = ?", [sprintf('%02d', $today->month)])
                        ->whereRaw("strftime('%d', tgl_lahir) = ?", [sprintf('%02d', $today->day)]);
                } else {
                    $query->whereRaw('MONTH(tgl_lahir) = ?', [$today->month])
                        ->whereRaw('DAY(tgl_lahir) = ?', [$today->day]);
                }

                return $query->orderBy('nama')
                    ->get(['id', 'nama', 'jabatan_nama', 'tgl_lahir', 'foto']);
            }
        );
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.admin.dashboard', [
            // 'jenisKelaminColumnChart' => $this->getJenisKelaminColumnChart(),
            // 'jenisKelaminPieChart' => $this->getJenisKelaminPieChart(),

            // 'pendidikanColumnChart' => $this->getPendidikanColumnChart(),
            // 'pendidikanPieChart' => $this->getPendidikanPieChart(),

            // 'jenisJabatanColumnChart' => $this->getJenisJabatanColumnChart(),
            // 'jenisJabatanPieChart' => $this->getJenisJabatanPieChart(),

            // 'rangeUmurColumnChart' => $this->getRangeUmurColumnChart(),
            // 'rangeUmurPieChart' => $this->getRangeUmurPieChart(),

            'birthdayEmployees' => $this->birthdayEmployees,
        ]);
    }
}
