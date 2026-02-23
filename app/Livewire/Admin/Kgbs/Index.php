<?php

namespace App\Livewire\Admin\Kgbs;

use App\Services\Kgb\ExportKgbService;
use App\Services\Kgb\KgbService;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class Index extends Component
{
    #[Url]
    public ?string $kabKota = null;

    #[Url]
    public int $monthsAhead = 6;

    public array $kabKotaOptions = [];

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

    public function render()
    {
        return view('livewire.admin.kgbs.index');
    }
}
