<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use App\Services\Statistic\PegawaiStatisticService;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;
use Asantibanez\LivewireCharts\Models\PieChartModel;

class RangeUmurSection extends Component
{
    public ?string $kabKota = null;

    public array $chartData = [];

    private array $colors = [
        '#a6192e',
        '#e5ad25',
        '#7b1822',
        '#f4c542',
        '#cf4b58',
        '#c58a12',
        '#5b1119',
    ];

    public function mount(
        ?string $kabKota = null,
        PegawaiStatisticService $service
    ) {
        $this->kabKota = $kabKota;

        $stats = $service->getAllStats($kabKota);

        $this->chartData = $stats['range_umur_chart'] ?? [];
    }

    private function makeColumnChart(): ColumnChartModel
    {
        $chart = (new ColumnChartModel)
            ->setTitle('Distribusi Range Umur')
            ->setAnimated(true)
            ->withDataLabels()
            ->withoutLegend()
            ->setColumnWidth(62)
            ->setJsonConfig([
                'chart.fontFamily' => "'Inter, ui-sans-serif, system-ui, sans-serif'",
                'yaxis.title.text' => "''",
                'xaxis.labels.style.fontSize' => "'12px'",
                'yaxis.labels.style.fontSize' => "'12px'",
                'dataLabels.style.fontSize' => "'12px'",
                'dataLabels.style.fontWeight' => '600',
            ]);

        foreach ($this->chartData['labels'] ?? [] as $i => $label) {
            $chart->addColumn(
                $label,
                $this->chartData['values'][$i] ?? 0,
                $this->colors[$i % count($this->colors)]
            );
        }

        return $chart;
    }

    private function makePieChart(): PieChartModel
    {
        $chart = (new PieChartModel)
            ->setTitle('Persentase Range Umur')
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

        foreach ($this->chartData['labels'] ?? [] as $i => $label) {
            $chart->addSlice(
                $label,
                $this->chartData['values'][$i] ?? 0,
                $this->colors[$i % count($this->colors)]
            );
        }

        return $chart;
    }

    public function render()
    {
        return view('livewire.dashboard.range-umur-section', [
            'rangeUmurColumnChart' => $this->makeColumnChart(),
            'rangeUmurPieChart' => $this->makePieChart(),
        ]);
    }
}
