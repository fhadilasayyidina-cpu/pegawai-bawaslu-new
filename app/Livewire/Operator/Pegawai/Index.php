<?php

namespace App\Livewire\Operator\Pegawai;

use App\Livewire\Admin\Pegawai\Index as AdminIndex;

class Index extends AdminIndex
{
    public array $breadcrumbs = [
        ['label' => 'Dashboard', 'link' => '/operator'],
        ['label' => 'Data Pegawai', 'link' => '#'],
    ];

    // Filtering by access_scope happens in PegawaiService automatically
}
