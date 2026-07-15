<?php

namespace App\Livewire\Admin\Pimpinan;

use App\Models\Pimpinan;
use Livewire\Component;

class Details extends Component
{
    public ?Pimpinan $pimpinan = null;

    public array $breadcrumbs = [];

    public function mount($id): void
    {
        $this->pimpinan = Pimpinan::find($id);

        if (! $this->pimpinan) {
            abort(404);
        }

        $this->breadcrumbs = [
            ['label' => 'Dashboard', 'link' => '/admin'],
            ['label' => 'Data Pimpinan', 'link' => '/admin/pimpinans'],
            ['label' => $this->pimpinan->nama, 'link' => '#'],
        ];
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.admin.pimpinan.details');
    }
}
