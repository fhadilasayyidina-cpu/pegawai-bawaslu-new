<?php

namespace App\Livewire\Admin\Cuti;

use App\Models\Cuti;
use App\Services\Cuti\CutiService;
use Livewire\Component;

class Edit extends Component
{
    public int $id;

    public Cuti $cuti;

    public string $nomor_surat = '';

    public string $jenis_cuti = 'tahunan';

    public string $alasan = '';

    public string $tanggal_mulai = '';

    public string $tanggal_selesai = '';

    public int $lama_hari = 0;

    public ?string $keterangan = null;

    public string $nama_kepala_sekretariat = '';

    public ?string $nip_kepala_sekretariat = null;

    public string $nama_sekjen = '';

    public ?string $nip_sekjen = null;

    public ?string $nomor_surat_edaran = null;

    public array $breadcrumbs = [];

    public function mount(int $id, CutiService $cutiService): void
    {
        $this->cuti = $cutiService->getById($id);

        $this->nomor_surat = $this->cuti->nomor_surat;
        $this->jenis_cuti = $this->cuti->jenis_cuti;
        $this->alasan = $this->cuti->alasan;
        $this->tanggal_mulai = $this->cuti->tanggal_mulai->format('Y-m-d');
        $this->tanggal_selesai = $this->cuti->tanggal_selesai->format('Y-m-d');
        $this->lama_hari = $this->cuti->lama_hari;
        $this->keterangan = $this->cuti->keterangan;
        $this->nama_kepala_sekretariat = $this->cuti->nama_kepala_sekretariat;
        $this->nip_kepala_sekretariat = $this->cuti->nip_kepala_sekretariat;
        $this->nama_sekjen = $this->cuti->nama_sekjen;
        $this->nip_sekjen = $this->cuti->nip_sekjen;
        $this->nomor_surat_edaran = $this->cuti->nomor_surat_edaran;

        $this->breadcrumbs = [
            ['label' => 'Admin', 'link' => '/admin'],
            ['label' => 'Cuti', 'link' => '/admin/cutis'],
            ['label' => 'Edit', 'link' => '#'],
        ];
    }

    public function updatedTanggalMulai(): void
    {
        $this->calculateLamaHari();
    }

    public function updatedTanggalSelesai(): void
    {
        $this->calculateLamaHari();
    }

    private function calculateLamaHari(): void
    {
        if ($this->tanggal_mulai && $this->tanggal_selesai) {
            $start = \Illuminate\Support\Carbon::parse($this->tanggal_mulai);
            $end = \Illuminate\Support\Carbon::parse($this->tanggal_selesai);
            $this->lama_hari = $start->diffInDays($end) + 1;
        }
    }

    public function update(CutiService $cutiService)
    {
        $validated = $this->validate([
            'nomor_surat' => ['required', 'string', 'max:255'],
            'jenis_cuti' => ['required', 'in:tahunan'],
            'alasan' => ['required', 'string'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'lama_hari' => ['required', 'integer', 'min:1'],
            'keterangan' => ['nullable', 'string'],
            'nama_kepala_sekretariat' => ['required', 'string', 'max:255'],
            'nip_kepala_sekretariat' => ['nullable', 'string', 'max:255'],
            'nama_sekjen' => ['required', 'string', 'max:255'],
            'nip_sekjen' => ['nullable', 'string', 'max:255'],
            'nomor_surat_edaran' => ['nullable', 'string', 'max:255'],
        ]);

        $cutiService->update($this->cuti->id, $validated);

        $this->dispatch('notyf:show', [
            'type' => 'success',
            'message' => 'Data cuti berhasil diperbarui!',
        ]);

        return $this->redirect('/admin/cutis/'.$this->cuti->id);
    }

    public function render()
    {
        return view('livewire.admin.cuti.edit');
    }
}
