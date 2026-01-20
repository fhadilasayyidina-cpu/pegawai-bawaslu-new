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
        $this->kabKotaOptions = app(PegawaiService::class)->getKabKota()->toArray();
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

    public function render(): \Illuminate\View\View
    {
        $colors = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#06b6d4'];

        // Jenis Kelamin - Column Chart
        $jenisKelaminColumnChart = (new ColumnChartModel)
            ->setTitle('Distribusi Jenis Kelamin')
            ->setAnimated(true);

        foreach ($this->statistics['jenis_kelamin_chart']['labels'] ?? [] as $index => $label) {
            $color = $colors[$index % count($colors)];
            $jenisKelaminColumnChart->addColumn($label, $this->statistics['jenis_kelamin_chart']['values'][$index] ?? 0, $color);
        }

        // Jenis Kelamin - Pie Chart
        $jenisKelaminPieChart = (new PieChartModel)
            ->setTitle('Distribusi Jenis Kelamin')
            ->setAnimated(true);

        foreach ($this->statistics['jenis_kelamin_chart']['labels'] ?? [] as $index => $label) {
            $color = $colors[$index % count($colors)];
            $jenisKelaminPieChart->addSlice($label, $this->statistics['jenis_kelamin_chart']['values'][$index] ?? 0, $color);
        }

        // Tingkat Pendidikan - Column Chart
        $pendidikanColumnChart = (new ColumnChartModel)
            ->setTitle('Tingkat Pendidikan')
            ->setAnimated(true);

        foreach ($this->statistics['pendidikan_chart']['labels'] ?? [] as $index => $label) {
            $color = $colors[$index % count($colors)];
            $pendidikanColumnChart->addColumn($label, $this->statistics['pendidikan_chart']['values'][$index] ?? 0, $color);
        }

        // Tingkat Pendidikan - Pie Chart
        $pendidikanPieChart = (new PieChartModel)
            ->setTitle('Tingkat Pendidikan')
            ->setAnimated(true);

        foreach ($this->statistics['pendidikan_chart']['labels'] ?? [] as $index => $label) {
            $color = $colors[$index % count($colors)];
            $pendidikanPieChart->addSlice($label, $this->statistics['pendidikan_chart']['values'][$index] ?? 0, $color);
        }

        return view('livewire.admin.dashboard', compact(
            'jenisKelaminColumnChart',
            'jenisKelaminPieChart',
            'pendidikanColumnChart',
            'pendidikanPieChart',
        ));
    }
}
