<?php

namespace App\Livewire\Operator\Pimpinan;

use App\Livewire\Admin\Pimpinan\Details as AdminDetails;

class Details extends AdminDetails
{
    // Override breadcrumbs for operator
    public function mount($id): void
    {
        parent::mount($id);
        $this->breadcrumbs = [
            ['label' => 'Dashboard', 'link' => '/operator'],
            ['label' => 'Data Pimpinan', 'link' => '/operator/pimpinans'],
            ['label' => $this->pimpinan->nama, 'link' => '#'],
        ];
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.operator.pimpinan.details');
    }
}
