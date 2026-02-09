<?php

namespace App\Livewire\Admin\Pegawai;

use App\Models\Pegawai;
use App\Services\Absensi\AbsensiService;
use App\Services\Absensi\AbsensiStatisticService;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Absensis extends Component
{
    use WithPagination;

    public int $pegawaiId;

    public Pegawai $pegawai;

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
        ['key' => 'tanggal', 'label' => 'Tanggal'],
        ['key' => 'status', 'label' => 'Status'],
        ['key' => 'keterangan', 'label' => 'Keterangan'],
        ['key' => 'aksi', 'label' => 'Aksi'],
    ];

    public array $breadcrumbs = [];

    public function mount(int $pegawaiId): void
    {
        $this->pegawaiId = $pegawaiId;
        $this->pegawai = Pegawai::findOrFail($pegawaiId);
        $this->breadcrumbs = [
            ['label' => 'Dashboard', 'link' => '/admin'],
            ['label' => 'Pegawai', 'link' => '/admin/pegawais'],
            ['label' => $this->pegawai->nama, 'link' => '/admin/pegawais/'.$this->pegawai->id],
            ['label' => 'Absensi', 'link' => '#'],
        ];
    }

    public function getAbsensisProperty()
    {
        return app(AbsensiService::class)
            ->getAll(null, $this->tanggalMulai, $this->tanggalAkhir, $this->pegawai->id, $this->status);
    }

    public function getStatisticsProperty(): array
    {
        return app(AbsensiStatisticService::class)->getStatistics(
            $this->pegawai->id,
            $this->tanggalMulai,
            $this->tanggalAkhir
        );
    }

    public function delete(int $id): void
    {
        app(AbsensiService::class)->delete($id);

        $this->dispatch('notyf:show', [
            'type' => 'success',
            'message' => 'Absensi berhasil dihapus!',
        ]);
    }

    public function resetFilters(): void
    {
        $this->reset(['tanggalMulai', 'tanggalAkhir', 'status']);
    }

    public function render()
    {
        return view('livewire.admin.pegawai.absensis');
    }
}
