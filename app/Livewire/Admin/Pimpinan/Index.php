<?php

namespace App\Livewire\Admin\Pimpinan;

use App\Services\Pimpinan\PimpinanService;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public ?string $search = null;

    public ?string $kabKota = null;

    public array $kabKotaOptions = [];

    public array $tableHeaders = [
        ['key' => 'nomor', 'label' => 'No', 'class' => 'w-1'],
        ['key' => 'id', 'label' => 'ID', 'link' => true, 'hidden' => true],
        ['key' => 'nama', 'label' => 'Nama'],
        ['key' => 'jabatan', 'label' => 'Jabatan'],
        ['key' => 'kab_kota', 'label' => 'Kabupaten Kota'],
        ['key' => 'email', 'label' => 'Email'],
        ['key' => 'no_hp', 'label' => 'No HP'],
    ];

    public array $breadcrumbs = [
        ['label' => 'Dashboard', 'link' => '/admin'],
        ['label' => 'Data Pimpinan', 'link' => '#'],
    ];

    public function getPimpinansProperty()
    {
        return app(PimpinanService::class)
            ->getAllPimpinan($this->search, $this->kabKota);
    }

    public function mount()
    {
        $this->kabKotaOptions = app(PimpinanService::class)->getKabKota()->toArray();
    }

    public function delete(int $id)
    {
        app(PimpinanService::class)->deletePimpinan($id);

        $this->dispatch('notyf:show', [
            'type' => 'success',
            'message' => 'Pimpinan berhasil dihapus!',
        ]);
    }

    public function render()
    {
        return view('livewire.admin.pimpinan.index');
    }
}
