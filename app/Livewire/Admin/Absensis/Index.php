<?php

namespace App\Livewire\Admin\Absensis;

use App\Services\Absensi\AbsensiService;
use App\Services\Absensi\AbsensiStatisticService;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public ?string $search = null;

    #[Url]
    public ?string $tanggalMulai = null;

    #[Url]
    public ?string $tanggalAkhir = null;

    #[Url(as: 'pegawai_id')]
    public ?int $pegawaiId = null;

    #[Url]
    public ?string $status = null;

    public array $pegawaiOptions = [];

    public array $statusOptions = [
        ['id' => 'Hadir', 'name' => 'Hadir'],
        ['id' => 'Izin', 'name' => 'Izin'],
        ['id' => 'Cuti', 'name' => 'Cuti'],
        ['id' => 'Tidak Hadir', 'name' => 'Tidak Hadir'],
    ];

    public array $tableHeaders = [
        ['key' => 'nomor', 'label' => 'No', 'class' => 'w-1'],
        ['key' => 'tanggal', 'label' => 'Tanggal'],
        ['key' => 'pegawai', 'label' => 'Pegawai'],
        ['key' => 'status', 'label' => 'Status'],
        ['key' => 'keterangan', 'label' => 'Keterangan'],
    ];

    public array $breadcrumbs = [
        ['label' => 'Dashboard', 'link' => '/admin'],
        ['label' => 'Absensi', 'link' => '#'],
    ];

    public function getAbsensisProperty()
    {
        return app(AbsensiService::class)
            ->getAll($this->search, $this->tanggalMulai, $this->tanggalAkhir, $this->pegawaiId, $this->status);
    }

    public function getStatisticsProperty(): array
    {
        return app(AbsensiStatisticService::class)->getStatistics(
            $this->pegawaiId,
            $this->tanggalMulai,
            $this->tanggalAkhir
        );
    }

    public function mount()
    {
        $this->pegawaiOptions = app(AbsensiService::class)->getPegawaiOptions();
    }

    public function delete(int $id)
    {
        app(AbsensiService::class)->delete($id);

        $this->dispatch('notyf:show', [
            'type' => 'success',
            'message' => 'Absensi berhasil dihapus!',
        ]);
    }

    public function resetFilters()
    {
        $this->reset(['search', 'tanggalMulai', 'tanggalAkhir', 'pegawaiId', 'status']);
    }

    public function render()
    {
        return view('livewire.admin.absensis.index');
    }
}
