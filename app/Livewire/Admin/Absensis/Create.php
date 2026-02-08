<?php

namespace App\Livewire\Admin\Absensis;

use App\Services\Absensi\AbsensiService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Create extends Component
{
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

    public array $breadcrumbs = [
        ['label' => 'Dashboard', 'link' => '/admin'],
        ['label' => 'Absensi', 'link' => '/admin/absensis'],
        ['label' => 'Tambah Absensi', 'link' => '#'],
    ];

    public function mount(): void
    {
        $this->pegawaiOptions = app(AbsensiService::class)->getPegawaiOptions();
        $this->tanggal = now()->format('Y-m-d');
    }

    public function save(): void
    {
        $validated = $this->validate([
            'pegawai_id' => ['required', 'exists:pegawais,id'],
            'tanggal' => ['required', 'date'],
            'status' => ['required', 'in:Hadir,Izin,Cuti,Tidak Hadir'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $validated['created_by'] = Auth::id();

        try {
            app(AbsensiService::class)->create($validated);

            $this->dispatch('notyf:show', [
                'type' => 'success',
                'message' => 'Absensi berhasil disimpan!',
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
                    'message' => 'Terjadi kesalahan saat menyimpan data.',
                ]);
            }
        }
    }

    public function render()
    {
        return view('livewire.admin.absensis.create');
    }
}
