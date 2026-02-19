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

    public string $selectedTab = 'sulsel';

    public array $tableHeaders = [
        ['key' => 'nomor', 'label' => 'No', 'class' => 'w-1'],
        ['key' => 'id', 'label' => 'ID', 'link' => true, 'hidden' => true],
        ['key' => 'foto', 'label' => 'Foto'],
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
        $query = \App\Models\Pimpinan::query();

        // Filter by tab
        if ($this->selectedTab === 'sulsel') {
            // Show only Sulawesi Selatan
            $query->where('kab_kota', 'like', '%Sulawesi Selatan%');
        } else {
            // Show all except Sulawesi Selatan
            $query->where('kab_kota', 'not like', '%Sulawesi Selatan%');
        }

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('nama', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%')
                    ->orWhere('no_hp', 'like', '%'.$this->search.'%');
            });
        }

        // Apply kab/kota filter
        if ($this->kabKota) {
            $query->where('kab_kota', $this->kabKota);
        }

        return $query->orderBy('nama')->paginate(10);
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
