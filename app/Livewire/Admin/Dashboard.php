<?php

namespace App\Livewire\Admin;

use App\Services\Pegawai\PegawaiService;
use App\Services\Statistic\PegawaiStatisticService;
use Livewire\Component;

class Dashboard extends Component
{
    public ?string $kabKota = null;

    public array $kabKotaOptions = [];

    public array $statistics = [];

    public function mount(): void
    {
        $this->kabKotaOptions = app(PegawaiService::class)->getKabKota()->toArray();
        $this->loadStatistics();
    }

    public function updatedKabKota(): void
    {
        $this->loadStatistics();
    }

    private function loadStatistics(): void
    {
        $this->statistics = app(PegawaiStatisticService::class)->getAllStats($this->kabKota);
    }

    public function render(): \Illuminate\View\View
    {
        return view('livewire.admin.dashboard');
    }
}
