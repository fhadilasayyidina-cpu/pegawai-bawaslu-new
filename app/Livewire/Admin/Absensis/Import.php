<?php

namespace App\Livewire\Admin\Absensis;

use App\Services\Absensi\ImportAbsensiService;
use App\Services\Pegawai\PegawaiService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Mary\Traits\Toast;

class Import extends Component
{
    use Toast, WithFileUploads;

    public $file;

    public $kabKota = null;

    public string $jenisAbsen = 'wfo';

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
            'jenisAbsen' => ['required', 'string', 'in:wfo,wfh'],
        ]);

        $service = app(ImportAbsensiService::class);
        $filePath = $this->file->getRealPath();

        if ($this->jenisAbsen === 'wfo') {
            $this->result = $service->importAbsenWfo($filePath, Auth::id(), $this->kabKota);
        } else {
            $this->result = $service->importAbsenWfh($filePath, Auth::id(), $this->kabKota);
        }

        if ($this->result['success'] ?? false) {
            $this->success($this->result['message']);
        } else {
            $this->error($this->result['message'] ?? 'Import failed');
        }
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
