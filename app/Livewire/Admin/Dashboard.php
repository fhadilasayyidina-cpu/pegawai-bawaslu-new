<?php

namespace App\Livewire\Admin;

use App\Services\Pegawai\PegawaiService;
use App\Services\Statistic\PegawaiStatisticService;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;
use Asantibanez\LivewireCharts\Models\PieChartModel;
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

    /**
     * Fix for livewire-charts compatibility with Livewire 3.x
     * The library's JavaScript tries to call toJSON during updates.
     */
    public function toJson(): string
    {
        return json_encode([
            'kabKota' => $this->kabKota,
            'statistics' => $this->statistics,
        ]);
    }

    public function render(): \Illuminate\View\View
    {
        $colors = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#06b6d4'];

        // Jenis Kelamin - Column Chart
        $jenisKelaminColumnChart = (new ColumnChartModel)
            ->setTitle('Distribusi Jenis Kelamin')
            ->setAnimated(true)
            ->withDataLabels();

        foreach ($this->statistics['jenis_kelamin_chart']['labels'] ?? [] as $index => $label) {
            $color = $colors[$index % count($colors)];
            $jenisKelaminColumnChart->addColumn($label, $this->statistics['jenis_kelamin_chart']['values'][$index] ?? 0, $color);
        }

        // Jenis Kelamin - Pie Chart
        $jenisKelaminPieChart = (new PieChartModel)
            ->setTitle('Distribusi Jenis Kelamin')
            ->setAnimated(true)
            ->withDataLabels();

        foreach ($this->statistics['jenis_kelamin_chart']['labels'] ?? [] as $index => $label) {
            $color = $colors[$index % count($colors)];
            $jenisKelaminPieChart->addSlice($label, $this->statistics['jenis_kelamin_chart']['values'][$index] ?? 0, $color);
        }

        // Tingkat Pendidikan - Column Chart
        $pendidikanColumnChart = (new ColumnChartModel)
            ->setTitle('Tingkat Pendidikan')
            ->setAnimated(true)
            ->withDataLabels();

        foreach ($this->statistics['pendidikan_chart']['labels'] ?? [] as $index => $label) {
            $color = $colors[$index % count($colors)];
            $pendidikanColumnChart->addColumn($label, $this->statistics['pendidikan_chart']['values'][$index] ?? 0, $color);
        }

        // Tingkat Pendidikan - Pie Chart
        $pendidikanPieChart = (new PieChartModel)
            ->setTitle('Tingkat Pendidikan')
            ->setAnimated(true)
            ->withDataLabels();

        foreach ($this->statistics['pendidikan_chart']['labels'] ?? [] as $index => $label) {
            $color = $colors[$index % count($colors)];
            $pendidikanPieChart->addSlice($label, $this->statistics['pendidikan_chart']['values'][$index] ?? 0, $color);
        }

        // Jenis Jabatan - Column Chart
        $jenisJabatanColumnChart = (new ColumnChartModel)
            ->setTitle('Distribusi Jenis Jabatan')
            ->setAnimated(true)
            ->withDataLabels();

        foreach ($this->statistics['jenis_jabatan_chart']['labels'] ?? [] as $index => $label) {
            $color = $colors[$index % count($colors)];
            $displayLabel = $label ?: 'Tidak Teridentifikasi';
            $jenisJabatanColumnChart->addColumn($displayLabel, $this->statistics['jenis_jabatan_chart']['values'][$index] ?? 0, $color);
        }

        // Jenis Jabatan - Pie Chart
        $jenisJabatanPieChart = (new PieChartModel)
            ->setTitle('Persentase Jenis Jabatan')
            ->setAnimated(true)
            ->withDataLabels();

        foreach ($this->statistics['jenis_jabatan_chart']['labels'] ?? [] as $index => $label) {
            $color = $colors[$index % count($colors)];
            $displayLabel = $label ?: 'Tidak Teridentifikasi';
            $jenisJabatanPieChart->addSlice($displayLabel, $this->statistics['jenis_jabatan_chart']['values'][$index] ?? 0, $color);
        }

        // Range Umur - Column Chart
        $rangeUmurColumnChart = (new ColumnChartModel)
            ->setTitle('Distribusi Range Umur')
            ->setAnimated(true)
            ->withDataLabels();

        foreach ($this->statistics['range_umur_chart']['labels'] ?? [] as $index => $label) {
            $color = $colors[$index % count($colors)];
            $displayLabel = $label ?: 'Tidak Teridentifikasi';
            $rangeUmurColumnChart->addColumn($displayLabel, $this->statistics['range_umur_chart']['values'][$index] ?? 0, $color);
        }

        // Range Umur - Pie Chart
        $rangeUmurPieChart = (new PieChartModel)
            ->setTitle('Persentase Range Umur')
            ->setAnimated(true)
            ->withDataLabels();

        foreach ($this->statistics['range_umur_chart']['labels'] ?? [] as $index => $label) {
            $color = $colors[$index % count($colors)];
            $displayLabel = $label ?: 'Tidak Teridentifikasi';
            $rangeUmurPieChart->addSlice($displayLabel, $this->statistics['range_umur_chart']['values'][$index] ?? 0, $color);
        }

        return view('livewire.admin.dashboard', compact(
            'jenisKelaminColumnChart',
            'jenisKelaminPieChart',
            'pendidikanColumnChart',
            'pendidikanPieChart',
            'jenisJabatanColumnChart',
            'jenisJabatanPieChart',
            'rangeUmurColumnChart',
            'rangeUmurPieChart',
        ));
    }
}
