<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

class UmurSection extends Component
{
    public function placeholder()
    {
        return view('components.chart-section-skeleton');
    }

    public function render()
    {
        return view('livewire.dashboard.umur-section');
    }
}
