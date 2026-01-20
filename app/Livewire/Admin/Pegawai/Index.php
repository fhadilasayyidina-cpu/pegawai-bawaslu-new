<?php

namespace App\Livewire\Admin\Pegawai;

use App\Services\Pegawai\ImportPegawaiService;
use App\Services\Pegawai\PegawaiService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    use WithFileUploads, WithPagination;

    public ?string $search = null;

    public ?string $kabKota = null;

    public array $kabKotaOptions = [];

    public $file;

    public bool $myModal3 = false;

    public array $tableHeaders = [
        ['key' => 'nomor', 'label' => 'No', 'class' => 'w-1'],
        ['key' => 'id', 'label' => 'ID', 'link' => true, 'hidden' => true],
        ['key' => 'nip_baru', 'label' => 'NIP'],
        ['key' => 'nama', 'label' => 'Nama'],
        ['key' => 'jabatan_nama', 'label' => 'Jabatan'],
        ['key' => 'kab_kota', 'label' => 'Kabupaten Kota'],
    ];

    public array $breadcrumbs = [
        ['label' => 'Dashboard', 'link' => '/admin'],
        ['label' => 'Manajemen Pegawai', 'link' => '#'],
    ];

    public function getPegawaisProperty()
    {
        return app(PegawaiService::class)
            ->getAllPegawai($this->search, $this->kabKota);
    }

    public function mount()
    {
        $this->kabKotaOptions = app(PegawaiService::class)->getKabKota()->toArray();
    }

    public function delete(int $id)
    {
        app(PegawaiService::class)->deletePegawai($id);

        $this->dispatch('notyf:show', [
            'type' => 'success',
            'message' => 'Pegawai berhasil dihapus!',
        ]);
    }

    public function import()
    {

        $path = $this->file->store('pegawai/import', 'public');

        $fullPath = storage_path('app/public/'.$path);

        app(ImportPegawaiService::class)->import($fullPath);
    }

    public function render()
    {
        return view('livewire.admin.pegawai.index');
    }
}
