<?php

namespace App\Livewire\Admin\Cuti;

use App\Models\Cuti;
use App\Services\Cuti\CutiBesarService;
use App\Services\Cuti\CutiService;
use App\Services\Cuti\CutiTahunanService;
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

    public function delete(
        int $id,
        CutiService $cutiService,
        CutiTahunanService $cutiTahunanService,
        CutiBesarService $cutiBesarService
    ) {
        $cuti = $cutiService->getById($id);

        // Restore jatah cuti sebelum hapus (hanya untuk cuti tahunan dan cuti besar)
        if ($cuti->jenis_cuti === 'besar') {
            $cutiBesarService->restoreJatahCutiBesar($cuti);
        } elseif ($cuti->jenis_cuti === 'tahunan') {
            $cutiTahunanService->restoreJatahCuti($cuti);
        }
        // Cuti sakit, melahirkan, alasan penting, luar tanggungan tidak mempengaruhi jatah

        // Hapus data cuti
        $cutiService->delete($id);

        $this->dispatch('notyf:show', [
            'type' => 'success',
            'message' => 'Data cuti berhasil dihapus!',
        ]);
    }
}
