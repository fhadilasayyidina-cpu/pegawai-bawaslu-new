<?php

namespace App\Livewire\Operator\Pimpinan;

use App\Livewire\Admin\Pimpinan\Index as AdminIndex;

class Index extends AdminIndex
{
    public array $breadcrumbs = [
        ['label' => 'Dashboard', 'link' => '/operator'],
        ['label' => 'Data Pimpinan', 'link' => '#'],
    ];

    // Filtering by access_scope happens in PimpinanService automatically
}
