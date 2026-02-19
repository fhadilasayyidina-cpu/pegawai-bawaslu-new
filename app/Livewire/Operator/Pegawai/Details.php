<?php

namespace App\Livewire\Operator\Pegawai;

use App\Livewire\Admin\Pegawai\Details as AdminDetails;

class Details extends AdminDetails
{
    // Override breadcrumbs for operator
    public function mount($id): void
    {
        parent::mount($id);
        $this->breadcrumbs = [
            ['label' => 'Dashboard', 'link' => '/operator'],
            ['label' => 'Data Pegawai', 'link' => '/operator/pegawais'],
            ['label' => $this->pegawai->nama, 'link' => '#'],
        ];
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.operator.pegawai.details');
    }
}
