<?php

namespace App\Livewire\Admin\Absensis;

use App\Services\Absensi\ImportAbsensiService;
use App\Services\Pegawai\PegawaiService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class Import extends Component
{
    use WithFileUploads;

    public $file;

    public $kabKota = null;

    public $result = null;

    public array $breadcrumbs = [
        ['label' => 'Dashboard', 'link' => '/admin'],
        ['label' => 'Data Absensi', 'link' => '/admin/absensis'],
        ['label' => 'Import', 'link' => '#'],
    ];

    public function import()
    {
        $this->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:10240'],
            'kabKota' => ['nullable', 'string'],
        ]);

        $service = app(ImportAbsensiService::class);
        $filePath = $this->file->getRealPath();

        $this->result = $service->import($filePath, Auth::id(), $this->kabKota);

        $this->dispatch('notyf:show', [
            'type' => $this->result['success'] ? 'success' : 'error',
            'message' => $this->result['message'],
        ]);
    }

    public function getKabKotaOptionsProperty(): array
    {
        return app(PegawaiService::class)->getKabKota()->toArray();
    }

    public function render()
    {
        return view('livewire.admin.absensis.import');
    }
}
