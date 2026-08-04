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

    #[Url]
    public ?string $status = null;

    public array $statusOptions = [
        ['id' => 'Hadir', 'name' => 'Hadir'],
        ['id' => 'Izin', 'name' => 'Izin'],
        ['id' => 'Cuti', 'name' => 'Cuti'],
        ['id' => 'Tidak Hadir', 'name' => 'Tidak Hadir'],
    ];

    public array $tableHeaders = [
        ['key' => 'nomor', 'label' => 'No', 'class' => 'w-1'],
        ['key' => 'pegawai', 'label' => 'Pegawai'],
        ['key' => 'tanggal', 'label' => 'Tanggal'],
        ['key' => 'jenis_absen', 'label' => 'Tipe'],
        ['key' => 'scan_masuk', 'label' => 'Scan Masuk'],
        ['key' => 'scan_pulang', 'label' => 'Scan Keluar'],
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
            ->getAll($this->search, $this->tanggalMulai, $this->tanggalAkhir, null, $this->status);
    }

    public function getStatisticsProperty(): array
    {
        return app(AbsensiStatisticService::class)->getStatistics(
            null,
            $this->tanggalMulai,
            $this->tanggalAkhir
        );
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
        $this->reset(['search', 'tanggalMulai', 'tanggalAkhir', 'status']);
    }

    public function render()
    {
        return view('livewire.admin.absensis.index');
    }
}
