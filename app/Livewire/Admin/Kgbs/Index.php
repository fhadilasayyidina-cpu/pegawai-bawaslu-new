<?php

namespace App\Livewire\Admin\Kgbs;

use App\Services\Kgb\KgbService;
use Livewire\Attributes\Url;
use Livewire\Component;

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

    public function getKgbListProperty()
    {
        return app(KgbService::class)->getUpcomingKgb($this->monthsAhead, $this->kabKota);
    }

    public function getStatisticsProperty(): array
    {
        return app(KgbService::class)->getStatistics($this->monthsAhead, $this->kabKota);
    }

    public function render()
    {
        return view('livewire.admin.kgbs.index');
    }
}
