<?php

namespace App\Livewire\Admin\Cuti;

use App\Services\Cuti\CutiService;
use Livewire\Component;

class Index extends Component
{
    public string $search = '';

    public function render(CutiService $cutiService)
    {
        $cutis = $cutiService->getAll([
            'search' => $this->search,
        ]);

        return view('livewire.admin.cuti.index', [
            'cutis' => $cutis,
        ]);
    }

    public function delete(int $id, CutiService $cutiService)
    {
        $cutiService->delete($id);

        $this->dispatch('notyf:show', [
            'type' => 'success',
            'message' => 'Data cuti berhasil dihapus!',
        ]);
    }
}
