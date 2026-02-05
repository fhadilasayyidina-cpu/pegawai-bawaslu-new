<?php

namespace App\Livewire\Admin\Cuti;

use App\Models\Cuti;
use App\Services\Cuti\CutiBesarService;
use App\Services\Cuti\CutiService;
use App\Services\Cuti\CutiTahunanService;
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

    public function delete(
        CutiService $cutiService,
        CutiTahunanService $cutiTahunanService,
        CutiBesarService $cutiBesarService
    ) {
        // Restore jatah cuti sebelum hapus (hanya untuk cuti tahunan dan cuti besar)
        if ($this->cuti->jenis_cuti === 'besar') {
            $cutiBesarService->restoreJatahCutiBesar($this->cuti);
        } elseif ($this->cuti->jenis_cuti === 'tahunan') {
            $cutiTahunanService->restoreJatahCuti($this->cuti);
        }
        // Cuti sakit, melahirkan, alasan penting, luar tanggungan tidak mempengaruhi jatah

        // Hapus data cuti
        $cutiService->delete($this->cuti->id);

        $this->dispatch('toast', type: 'success', message: 'Data cuti berhasil dihapus!');

        return $this->redirect('/admin/cutis');
    }

    public function render()
    {
        return view('livewire.admin.cuti.details');
    }
}
