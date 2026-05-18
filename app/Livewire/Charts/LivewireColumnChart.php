<?php

namespace App\Livewire\Charts;

use Asantibanez\LivewireCharts\Charts\LivewireColumnChart as BaseChart;

class LivewireColumnChart extends BaseChart
{
    /**
     * Fix for Livewire 3 compatibility where AlpineJS attempts to call toJSON on the $wire proxy.
     */
    public function toJSON(): array
    {
        return [];
    }
}
