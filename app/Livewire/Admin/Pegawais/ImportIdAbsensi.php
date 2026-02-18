<?php

namespace App\Livewire\Admin\Pegawais;

use App\Services\Pegawai\ImportIdAbsensiService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class ImportIdAbsensi extends Component
{
    use WithFileUploads;

    public $file;

    public $result = null;

    public array $breadcrumbs = [
        ['label' => 'Dashboard', 'link' => '/admin'],
        ['label' => 'Data Absensi', 'link' => '/admin/absensis'],
        ['label' => 'Import ID Absensi', 'link' => '#'],
    ];

    public function import()
    {
        $this->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls', 'max:10240'],
        ]);

        $service = app(ImportIdAbsensiService::class);
        $filePath = $this->file->getRealPath();

        $this->result = $service->import($filePath, Auth::id());

        $this->dispatch('notyf:show', [
            'type' => $this->result['success'] ? 'success' : 'error',
            'message' => $this->result['message'],
        ]);
    }

    public function downloadTemplate()
    {
        return Storage::disk('public')->download('templates/id-absensi-template.xlsx');
    }

    public function render()
    {
        return view('livewire.admin.pegawais.import-id-absensi');
    }
}
