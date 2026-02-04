<?php

namespace App\Livewire\Admin\Cuti;

use App\Models\Cuti;
use App\Models\Pegawai;
use App\Services\Cuti\CutiBesarService;
use App\Services\Cuti\CutiService;
use App\Services\Cuti\CutiTahunanService;
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

    public array $jatahCutiInfo = [];

    public function mount(CutiTahunanService $cutiTahunanService, CutiBesarService $cutiBesarService): void
    {
        if (request()->has('pegawai_id')) {
            $this->pegawai_id = (int) request('pegawai_id');
        }

        $this->pegawaiOptions = app(PegawaiService::class)->getAllForSelect();

        if ($this->pegawai_id > 0) {
            $this->updateJatahCutiInfo($cutiTahunanService, $cutiBesarService);
        }
    }

    public function updatedPegawaiId($value, CutiTahunanService $cutiTahunanService, CutiBesarService $cutiBesarService): void
    {
        $this->updateJatahCutiInfo($cutiTahunanService, $cutiBesarService);
    }

    public function updatedJenisCuti($value, CutiTahunanService $cutiTahunanService, CutiBesarService $cutiBesarService): void
    {
        $this->updateJatahCutiInfo($cutiTahunanService, $cutiBesarService);
    }

    private function updateJatahCutiInfo(CutiTahunanService $tahunanService, CutiBesarService $besarService): void
    {
        $this->jatahCutiInfo = [];

        if ($this->pegawai_id <= 0) {
            return;
        }

        $pegawai = Pegawai::find($this->pegawai_id);

        if (! $pegawai) {
            return;
        }

        if ($this->jenis_cuti === 'besar') {
            // Info untuk cuti besar
            $this->jatahCutiInfo = $besarService->getInfoCutiBesar($pegawai);
        } else {
            // Info untuk cuti tahunan
            $kelayakan = $tahunanService->cekKelayakanCuti($pegawai);

            $this->jatahCutiInfo = [
                'layak' => $kelayakan['layak'],
                'alasan' => $kelayakan['alasan'] ?? [],
                'masa_kerja_bulan' => $tahunanService->hitungMasaKerjaBulan($pegawai),
                'jatah_tersedia' => $kelayakan['layak'] ? $tahunanService->hitungJatahTersedia($pegawai) : 0,
                'rincian' => [
                    'tahun_berjalan' => $pegawai->sisa_cuti_tahun_berjalan ?? 12,
                    'tahun_lalu' => $pegawai->sisa_cuti_tahun_lalu ?? 0,
                    'dua_tahun_lalu' => $pegawai->sisa_cuti_dua_tahun_lalu ?? 0,
                ],
            ];
        }
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

    public function save(
        CutiService $cutiService,
        CutiTahunanService $cutiTahunanService,
        CutiBesarService $cutiBesarService
    ) {
        $pegawai = Pegawai::find($this->pegawai_id);

        if (! $pegawai) {
            $this->dispatch('toast', type: 'error', message: 'Pegawai tidak ditemukan.');

            return;
        }

        // Validasi berdasarkan jenis cuti
        if ($this->jenis_cuti === 'besar') {
            $validasi = $cutiBesarService->validasiCutiBesar($pegawai, $this->lama_hari);

            if (! $validasi['valid']) {
                $this->dispatch('toast', type: 'error', message: $validasi['pesan']);

                return;
            }
        } else {
            $validasi = $cutiTahunanService->validasiJumlahHari($pegawai, $this->lama_hari);

            if (! $validasi['valid']) {
                $this->dispatch('toast', type: 'error', message: $validasi['pesan']);

                return;
            }
        }

        $validated = $this->validate([
            'pegawai_id' => ['required', 'exists:pegawais,id'],
            'nomor_surat' => ['required', 'string', 'max:255'],
            'jenis_cuti' => ['required', 'in:tahunan,besar'],
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

        // Buat cuti
        $cutiService->create($validated);

        // Update sisa jatah cuti pegawai berdasarkan jenis
        if ($this->jenis_cuti === 'besar') {
            $cutiBesarService->prosesPengambilanCutiBesar($pegawai, $this->lama_hari);
        } else {
            $cutiTahunanService->prosesPengambilanCuti($pegawai, $this->lama_hari);
        }

        $this->dispatch('toast', type: 'success', message: 'Data cuti berhasil disimpan!');

        return $this->redirect('/admin/cutis');
    }

    public function render()
    {
        return view('livewire.admin.cuti.create');
    }
}
