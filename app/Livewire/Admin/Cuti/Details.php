<?php

namespace App\Livewire\Admin\Cuti;

use App\Models\Cuti;
use App\Services\Cuti\CutiService;
use Livewire\Component;

class Details extends Component
{
    public int $id;

    public Cuti $cuti;

    public array $breadcrumbs = [];

    public function mount(int $id, CutiService $cutiService): void
    {
        $this->cuti = $cutiService->getById($id);
        $this->breadcrumbs = [
            ['label' => 'Admin', 'link' => '/admin'],
            ['label' => 'Cuti', 'link' => '/admin/cutis'],
            ['label' => 'Detail', 'link' => '#'],
        ];
    }

    public function delete(CutiService $cutiService)
    {
        $cutiService->delete($this->cuti->id);

        $this->dispatch('notyf:show', [
            'type' => 'success',
            'message' => 'Data cuti berhasil dihapus!',
        ]);

        return $this->redirect('/admin/cutis');
    }

    public function render()
    {
        return view('livewire.admin.cuti.details');
    }
}
