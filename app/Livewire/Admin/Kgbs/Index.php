<?php

namespace App\Livewire\Admin\Kgbs;

use App\Models\KgbRecord;
use App\Services\Kgb\ExportKgbService;
use App\Services\Kgb\KgbService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class Index extends Component
{
    use WithFileUploads;

    #[Url]
    public ?string $kabKota = null;

    #[Url]
    public int $monthsAhead = 0; // Default: Semua

    public array $kabKotaOptions = [];

    public $fileGaji;

    public bool $modalImportGaji = false;

    public ?array $importGajiResult = null;

    public array $monthsOptions = [
        ['id' => 0, 'name' => 'Semua'],
        ['id' => 1, 'name' => '1 Bulan'],
        ['id' => 3, 'name' => '3 Bulan'],
        ['id' => 6, 'name' => '6 Bulan'],
        ['id' => 12, 'name' => '1 Tahun'],
        ['id' => 24, 'name' => '2 Tahun'],
    ];

    public array $breadcrumbs = [
        ['label' => 'Dashboard', 'link' => '/admin/dashboard'],
        ['label' => 'KGB', 'link' => '#'],
    ];

    public function mount(): void
    {
        $this->loadKabKotaOptions();
    }

    private function loadKabKotaOptions(): void
    {
        $kabKotaList = \App\Models\Pegawai::select('kab_kota')
            ->whereNotNull('kab_kota')
            ->distinct()
            ->orderBy('kab_kota')
            ->pluck('kab_kota', 'kab_kota')
            ->map(fn ($name) => ['id' => $name, 'name' => $name])
            ->values()
            ->toArray();

        array_unshift($kabKotaList, ['id' => '', 'name' => 'Semua Kabupaten/Kota']);

        $this->kabKotaOptions = $kabKotaList;
    }

    #[Computed]
    public function kgbList()
    {
        return app(KgbService::class)->getUpcomingKgb($this->monthsAhead, $this->kabKota);
    }

    #[Computed]
    public function statistics(): array
    {
        return app(KgbService::class)->getStatistics($this->monthsAhead, $this->kabKota);
    }

    public function export(): BinaryFileResponse
    {
        $kgbList = $this->kgbList;
        $fileName = 'kgb_export_'.date('Y-m-d_His').'.xlsx';
        $filePath = storage_path('app/temp/'.$fileName);

        app(ExportKgbService::class)->export($kgbList, $filePath);

        $this->dispatch('notyf:show', [
            'type' => 'success',
            'message' => 'Data KGB berhasil diexport!',
        ]);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    public function exportGaji(): BinaryFileResponse
    {
        $fileName = 'data_gaji_kgb_'.date('Y-m-d_His').'.xlsx';
        $filePath = storage_path('app/temp/'.$fileName);

        app(\App\Services\Kgb\ExportSalaryService::class)->export($filePath);

        $this->dispatch('notyf:show', [
            'type' => 'success',
            'message' => 'Data Gaji KGB berhasil diexport!',
        ]);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    public function delete(int $id): void
    {
        $kgb = KgbRecord::findOrFail($id);
        $kgb->delete();

        $this->dispatch('notyf:show', [
            'type' => 'success',
            'message' => 'Riwayat KGB berhasil dihapus!',
        ]);
    }

    public function importGaji(): void
    {
        $this->validate([
            'fileGaji' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:10240'],
        ]);

        try {
            $this->importGajiResult = app(\App\Services\Kgb\ImportSalaryService::class)->import($this->fileGaji->getRealPath());

            $this->dispatch('notyf:show', [
                'type' => 'success',
                'message' => "Import data gaji berhasil! {$this->importGajiResult['imported']} data nominal gaji diproses.",
            ]);

            $this->modalImportGaji = false;
            $this->reset('fileGaji');
        } catch (\Exception $e) {
            $this->dispatch('notyf:show', [
                'type' => 'error',
                'message' => 'Gagal mengimport data gaji: '.$e->getMessage(),
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.kgbs.index');
    }
}
