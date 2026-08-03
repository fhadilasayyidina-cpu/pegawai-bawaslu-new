<?php

namespace App\Livewire\Admin\Pegawais;

use App\Services\Absensi\ImportAbsensiService;
use App\Services\Pegawai\ImportIdAbsensiService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Mary\Traits\Toast;


class ImportIdAbsensi extends Component
{
    use WithFileUploads, Toast;

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

        $service = app(ImportAbsensiService::class);
        $filePath = $this->file->getRealPath();

        $this->result = $service->importAbsensiId($filePath);

        if ($this->result['status'] ?? false) {
            $this->success($this->result['message']);
        } else {
            $this->error($this->result['message'] ?? 'Import failed');
        }
    }

    public function downloadTemplate()
    {
        return app(ImportAbsensiService::class)->downloadTemplateImportAbsensiId();
    }

    public function render()
    {
        return view('livewire.admin.pegawais.import-id-absensi');
    }
}
