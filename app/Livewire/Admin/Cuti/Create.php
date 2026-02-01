<?php

namespace App\Livewire\Admin\Cuti;

use App\Services\Cuti\CutiService;
use App\Services\Pegawai\PegawaiService;
use Livewire\Component;

class Create extends Component
{
    public int $pegawai_id = 0;

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

    public array $pegawaiOptions = [];

    public function mount(): void
    {
        if (request()->has('pegawai_id')) {
            $this->pegawai_id = (int) request('pegawai_id');
        }

        $this->pegawaiOptions = app(PegawaiService::class)->getAllForSelect();
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

    public function save(CutiService $cutiService)
    {
        $validated = $this->validate([
            'pegawai_id' => ['required', 'exists:pegawais,id'],
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

        $cutiService->create($validated);

        $this->dispatch('notyf:show', [
            'type' => 'success',
            'message' => 'Data cuti berhasil disimpan!',
        ]);

        return $this->redirect('/admin/cutis');
    }

    public function render()
    {
        return view('livewire.admin.cuti.create');
    }
}
