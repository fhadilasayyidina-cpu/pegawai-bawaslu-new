<?php

namespace App\Livewire\Admin\Kgbs;

use App\Services\Kgb\ImportKgbService;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('Import Data KGB')]
class Import extends Component
{
    use WithFileUploads;

    public $file;

    public array $importResult = [];

    public bool $isImporting = false;

    public array $breadcrumbs = [
        ['label' => 'Dashboard', 'link' => '/admin/dashboard'],
        ['label' => 'KGB', 'link' => '/admin/kgbs'],
        ['label' => 'Import', 'link' => '#'],
    ];

    public function submit(): void
    {
        $this->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $this->isImporting = true;

        // Store file temporarily
        $filePath = $this->file->store('temp', 'local');

        try {
            $result = app(ImportKgbService::class)->import(storage_path('app/'.$filePath));

            $this->importResult = $result;

            if ($result['imported'] > 0) {
                $this->dispatch('notyf:show', [
                    'type' => 'success',
                    'message' => "Berhasil mengimport {$result['imported']} data KGB!",
                ]);
            }

            if ($result['failed'] > 0 || $result['skipped'] > 0) {
                $this->dispatch('notyf:show', [
                    'type' => 'warning',
                    'message' => "{$result['skipped']} dilewati, {$result['failed']} gagal.",
                ]);
            }

            // Reset file
            $this->reset('file');
        } catch (\Exception $e) {
            $this->dispatch('notyf:show', [
                'type' => 'error',
                'message' => 'Terjadi kesalahan: '.$e->getMessage(),
            ]);
        } finally {
            // Clean up temp file
            if (file_exists(storage_path('app/'.$filePath))) {
                unlink(storage_path('app/'.$filePath));
            }

            $this->isImporting = false;
        }
    }

    public function downloadTemplate()
    {
        return response()->download(storage_path('app/templates/import_kgb_template.xlsx'));
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.admin.kgbs.import');
    }
}
