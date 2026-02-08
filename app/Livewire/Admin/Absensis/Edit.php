<?php

namespace App\Livewire\Admin\Absensis;

use App\Models\Absensi;
use App\Services\Absensi\AbsensiService;
use Livewire\Component;

class Edit extends Component
{
    public int $id;

    public Absensi $absensi;

    public int $pegawai_id = 0;

    public string $tanggal = '';

    public string $status = 'Hadir';

    public ?string $keterangan = null;

    public array $pegawaiOptions = [];

    public array $statusOptions = [
        ['id' => 'Hadir', 'name' => 'Hadir'],
        ['id' => 'Izin', 'name' => 'Izin'],
        ['id' => 'Cuti', 'name' => 'Cuti'],
        ['id' => 'Tidak Hadir', 'name' => 'Tidak Hadir'],
    ];

    public array $breadcrumbs = [];

    public function mount(int $id): void
    {
        $this->absensi = app(AbsensiService::class)->findById($id);

        if (! $this->absensi) {
            abort(404);
        }

        $this->pegawai_id = $this->absensi->pegawai_id;
        $this->tanggal = $this->absensi->tanggal->format('Y-m-d');
        $this->status = $this->absensi->status;
        $this->keterangan = $this->absensi->keterangan;

        $this->pegawaiOptions = app(AbsensiService::class)->getPegawaiOptions();

        $this->breadcrumbs = [
            ['label' => 'Dashboard', 'link' => '/admin'],
            ['label' => 'Absensi', 'link' => '/admin/absensis'],
            ['label' => 'Edit Absensi', 'link' => '#'],
        ];
    }

    public function update(): void
    {
        $validated = $this->validate([
            'pegawai_id' => ['required', 'exists:pegawais,id'],
            'tanggal' => ['required', 'date'],
            'status' => ['required', 'in:Hadir,Izin,Cuti,Tidak Hadir'],
            'keterangan' => ['nullable', 'string'],
        ]);

        try {
            app(AbsensiService::class)->update($this->absensi->id, $validated);

            $this->dispatch('notyf:show', [
                'type' => 'success',
                'message' => 'Absensi berhasil diperbarui!',
            ]);

            $this->redirect('/admin/absensis');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === 23000) {
                $this->dispatch('notyf:show', [
                    'type' => 'error',
                    'message' => 'Absensi untuk pegawai dan tanggal tersebut sudah ada!',
                ]);
            } else {
                $this->dispatch('notyf:show', [
                    'type' => 'error',
                    'message' => 'Terjadi kesalahan saat memperbarui data.',
                ]);
            }
        }
    }

    public function render()
    {
        return view('livewire.admin.absensis.edit');
    }
}
