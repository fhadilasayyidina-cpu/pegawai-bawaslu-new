<?php

namespace App\Livewire\Admin\Absensis;

use App\Models\Absensi;
use App\Services\Absensi\AbsensiService;
use Livewire\Component;

class Details extends Component
{
    public int $id;

    public Absensi $absensi;

    public array $breadcrumbs = [];

    public function mount(int $id): void
    {
        $this->absensi = app(AbsensiService::class)->findById($id);

        if (! $this->absensi) {
            abort(404);
        }

        $this->breadcrumbs = [
            ['label' => 'Dashboard', 'link' => '/admin'],
            ['label' => 'Absensi', 'link' => '/admin/absensis'],
            ['label' => 'Detail Absensi', 'link' => '#'],
        ];
    }

    public function delete(): void
    {
        app(AbsensiService::class)->delete($this->absensi->id);

        $this->dispatch('notyf:show', [
            'type' => 'success',
            'message' => 'Absensi berhasil dihapus!',
        ]);

        $this->redirect('/admin/absensis');
    }

    public function render()
    {
        return view('livewire.admin.absensis.details');
    }
}
