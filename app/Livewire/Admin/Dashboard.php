<?php

namespace App\Livewire\Admin;

use App\Models\Pegawai;
use App\Services\Pegawai\PegawaiService;
use App\Services\Statistic\PegawaiStatisticService;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;
use Asantibanez\LivewireCharts\Models\PieChartModel;
use Illuminate\Support\Carbon;
use Livewire\Component;

class Dashboard extends Component
{
    public ?string $kabKota = null;

    public array $kabKotaOptions = [];

    public array $statistics = [];

    public function mount(): void
    {
        $kabKotaList = app(PegawaiService::class)->getKabKota()->toArray();

        // Tambahkan opsi "Semua" di awal
        array_unshift($kabKotaList, (object) ['id' => '', 'name' => 'Semua Kabupaten/Kota']);

        $this->kabKotaOptions = $kabKotaList;
        $this->loadStatistics();
    }

    public function updatedKabKota(): void
    {
        $this->loadStatistics();
    }

    private function loadStatistics(): void
    {
        $this->statistics = app(PegawaiStatisticService::class)->getAllStats($this->kabKota);
    }

    public function getBirthdayEmployeesProperty()
    {
        $today = Carbon::today();

        return Pegawai::query()
            ->whereNotNull('tgl_lahir')
            ->whereRaw('MONTH(tgl_lahir) = ?', [$today->month])
            ->whereRaw('DAY(tgl_lahir) = ?', [$today->day])
            ->orderBy('nama')
            ->get(['id', 'nama', 'jabatan_nama', 'tgl_lahir', 'foto']);
    }

    private function makeColumnChart(string $title, bool $horizontal = false): ColumnChartModel
    {
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

        return $horizontal ? $chart->setHorizontal() : $chart;
    }

    private function makePieChart(string $title): PieChartModel
    {
        return (new PieChartModel)
            ->setTitle($title)
            ->setAnimated(true)
            ->withDataLabels()
            ->legendPositionBottom()
            ->legendHorizontallyAlignedCenter()
            ->setJsonConfig([
                'chart.fontFamily' => "'Inter, ui-sans-serif, system-ui, sans-serif'",
                'title.style.fontSize' => "'16px'",
                'dataLabels.style.fontSize' => "'12px'",
                'dataLabels.style.fontWeight' => '600',
                'legend.fontSize' => "'12px'",
                'legend.itemMargin.vertical' => '4',
            ]);
    }

    public function render(): \Illuminate\View\View
    {
        $colors = ['#a6192e', '#e5ad25', '#7b1822', '#f4c542', '#cf4b58', '#c58a12', '#5b1119'];

        // Jenis Kelamin - Column Chart
        $jenisKelaminColumnChart = $this->makeColumnChart('Distribusi Jenis Kelamin');

        foreach ($this->statistics['jenis_kelamin_chart']['labels'] ?? [] as $index => $label) {
            $color = $colors[$index % count($colors)];
            $jenisKelaminColumnChart->addColumn($label, $this->statistics['jenis_kelamin_chart']['values'][$index] ?? 0, $color);
        }

        // Jenis Kelamin - Pie Chart
        $jenisKelaminPieChart = $this->makePieChart('Distribusi Jenis Kelamin');

        foreach ($this->statistics['jenis_kelamin_chart']['labels'] ?? [] as $index => $label) {
            $color = $colors[$index % count($colors)];
            $jenisKelaminPieChart->addSlice($label, $this->statistics['jenis_kelamin_chart']['values'][$index] ?? 0, $color);
        }

        // Tingkat Pendidikan - Column Chart
        $pendidikanColumnChart = $this->makeColumnChart('Tingkat Pendidikan');

        foreach ($this->statistics['pendidikan_chart']['labels'] ?? [] as $index => $label) {
            $color = $colors[$index % count($colors)];
            $pendidikanColumnChart->addColumn($label, $this->statistics['pendidikan_chart']['values'][$index] ?? 0, $color);
        }

        // Tingkat Pendidikan - Pie Chart
        $pendidikanPieChart = $this->makePieChart('Tingkat Pendidikan');

        foreach ($this->statistics['pendidikan_chart']['labels'] ?? [] as $index => $label) {
            $color = $colors[$index % count($colors)];
            $pendidikanPieChart->addSlice($label, $this->statistics['pendidikan_chart']['values'][$index] ?? 0, $color);
        }

        // Jenis Jabatan - Column Chart
        $jenisJabatanColumnChart = $this->makeColumnChart('Distribusi Jenis Jabatan', true);

        foreach ($this->statistics['jenis_jabatan_chart']['labels'] ?? [] as $index => $label) {
            $color = $colors[$index % count($colors)];
            $displayLabel = $label ?: 'Tidak Teridentifikasi';
            $jenisJabatanColumnChart->addColumn($displayLabel, $this->statistics['jenis_jabatan_chart']['values'][$index] ?? 0, $color);
        }

        // Jenis Jabatan - Pie Chart
        $jenisJabatanPieChart = $this->makePieChart('Persentase Jenis Jabatan');

        foreach ($this->statistics['jenis_jabatan_chart']['labels'] ?? [] as $index => $label) {
            $color = $colors[$index % count($colors)];
            $displayLabel = $label ?: 'Tidak Teridentifikasi';
            $jenisJabatanPieChart->addSlice($displayLabel, $this->statistics['jenis_jabatan_chart']['values'][$index] ?? 0, $color);
        }

        // Range Umur - Column Chart
        $rangeUmurColumnChart = $this->makeColumnChart('Distribusi Range Umur');

        foreach ($this->statistics['range_umur_chart']['labels'] ?? [] as $index => $label) {
            $color = $colors[$index % count($colors)];
            $displayLabel = $label ?: 'Tidak Teridentifikasi';
            $rangeUmurColumnChart->addColumn($displayLabel, $this->statistics['range_umur_chart']['values'][$index] ?? 0, $color);
        }

        // Range Umur - Pie Chart
        $rangeUmurPieChart = $this->makePieChart('Persentase Range Umur');

        foreach ($this->statistics['range_umur_chart']['labels'] ?? [] as $index => $label) {
            $color = $colors[$index % count($colors)];
            $displayLabel = $label ?: 'Tidak Teridentifikasi';
            $rangeUmurPieChart->addSlice($displayLabel, $this->statistics['range_umur_chart']['values'][$index] ?? 0, $color);
        }

        $birthdayEmployees = $this->birthdayEmployees;

        return view('livewire.admin.dashboard', compact(
            'jenisKelaminColumnChart',
            'jenisKelaminPieChart',
            'pendidikanColumnChart',
            'pendidikanPieChart',
            'jenisJabatanColumnChart',
            'jenisJabatanPieChart',
            'rangeUmurColumnChart',
            'rangeUmurPieChart',
            'birthdayEmployees',
        ));
    }
}
