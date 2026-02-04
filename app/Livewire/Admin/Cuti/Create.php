<?php

namespace App\Livewire\Admin\Cuti;

use App\Models\Cuti;
use App\Models\Pegawai;
use App\Services\Cuti\CutiAlasanPentingService;
use App\Services\Cuti\CutiBesarService;
use App\Services\Cuti\CutiLuarTanggunganService;
use App\Services\Cuti\CutiMelahirkanService;
use App\Services\Cuti\CutiSakitService;
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

    // Fields untuk Cuti Sakit
    public ?string $status_dokter = null;

    public ?string $nama_dokter = null;

    public ?string $nomor_surat_dokter = null;

    // Fields untuk Cuti Melahirkan
    public ?string $jenis_melahirkan = null;

    public ?string $tanggal_perkiraan_lahir = null;

    // Fields untuk Cuti Luar Tanggungan
    public bool $tanpa_gaji = false;

    public ?string $alasan_luar_tanggungan = null;

    public array $pegawaiOptions = [];

    public array $jatahCutiInfo = [];

    public function mount(
        CutiTahunanService $cutiTahunanService,
        CutiBesarService $cutiBesarService,
        CutiSakitService $cutiSakitService,
        CutiMelahirkanService $cutiMelahirkanService,
        CutiAlasanPentingService $cutiAlasanPentingService,
        CutiLuarTanggunganService $cutiLuarTanggunganService
    ): void {
        if (request()->has('pegawai_id')) {
            $this->pegawai_id = (int) request('pegawai_id');
        }

        $this->pegawaiOptions = app(PegawaiService::class)->getAllForSelect();

        if ($this->pegawai_id > 0) {
            $this->updateJatahCutiInfo(
                $cutiTahunanService,
                $cutiBesarService,
                $cutiSakitService,
                $cutiMelahirkanService,
                $cutiAlasanPentingService,
                $cutiLuarTanggunganService
            );
        }
    }

    public function updatedPegawaiId(
        $value,
        CutiTahunanService $cutiTahunanService,
        CutiBesarService $cutiBesarService,
        CutiSakitService $cutiSakitService,
        CutiMelahirkanService $cutiMelahirkanService,
        CutiAlasanPentingService $cutiAlasanPentingService,
        CutiLuarTanggunganService $cutiLuarTanggunganService
    ): void {
        $this->updateJatahCutiInfo(
            $cutiTahunanService,
            $cutiBesarService,
            $cutiSakitService,
            $cutiMelahirkanService,
            $cutiAlasanPentingService,
            $cutiLuarTanggunganService
        );
    }

    public function updatedJenisCuti(
        $value,
        CutiTahunanService $cutiTahunanService,
        CutiBesarService $cutiBesarService,
        CutiSakitService $cutiSakitService,
        CutiMelahirkanService $cutiMelahirkanService,
        CutiAlasanPentingService $cutiAlasanPentingService,
        CutiLuarTanggunganService $cutiLuarTanggunganService
    ): void {
        $this->updateJatahCutiInfo(
            $cutiTahunanService,
            $cutiBesarService,
            $cutiSakitService,
            $cutiMelahirkanService,
            $cutiAlasanPentingService,
            $cutiLuarTanggunganService
        );
    }

    private function updateJatahCutiInfo(
        CutiTahunanService $tahunanService,
        CutiBesarService $besarService,
        CutiSakitService $sakitService,
        CutiMelahirkanService $melahirkanService,
        CutiAlasanPentingService $alasanPentingService,
        CutiLuarTanggunganService $luarTanggunganService
    ): void {
        $this->jatahCutiInfo = [];

        if ($this->pegawai_id <= 0) {
            return;
        }

        $pegawai = Pegawai::find($this->pegawai_id);

        if (! $pegawai) {
            return;
        }

        switch ($this->jenis_cuti) {
            case 'besar':
                $this->jatahCutiInfo = $besarService->getInfoCutiBesar($pegawai);
                break;
            case 'sakit':
                $this->jatahCutiInfo = $sakitService->getInfoCutiSakit($pegawai);
                break;
            case 'melahirkan':
                $this->jatahCutiInfo = $melahirkanService->getInfoCutiMelahirkan($pegawai);
                break;
            case 'alasan_penting':
                $this->jatahCutiInfo = $alasanPentingService->getInfoCutiAlasanPenting($pegawai);
                break;
            case 'luar_tanggungan':
                $this->jatahCutiInfo = $luarTanggunganService->getInfoCutiLuarTanggungan($pegawai);
                break;
            default: // tahunan
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
                break;
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
        CutiBesarService $cutiBesarService,
        CutiSakitService $cutiSakitService,
        CutiMelahirkanService $cutiMelahirkanService,
        CutiAlasanPentingService $cutiAlasanPentingService,
        CutiLuarTanggunganService $cutiLuarTanggunganService
    ) {
        $pegawai = Pegawai::find($this->pegawai_id);

        if (! $pegawai) {
            $this->dispatch('toast', type: 'error', message: 'Pegawai tidak ditemukan.');

            return;
        }

        // Validasi berdasarkan jenis cuti
        switch ($this->jenis_cuti) {
            case 'besar':
                $validasi = $cutiBesarService->validasiCutiBesar($pegawai, $this->lama_hari);

                if (! $validasi['valid']) {
                    $this->dispatch('toast', type: 'error', message: $validasi['pesan']);

                    return;
                }

                $cutiBesarService->prosesPengambilanCutiBesar($pegawai, $this->lama_hari);
                break;
            case 'sakit':
                $validasi = $cutiSakitService->validasiCutiSakit($this->lama_hari, $this->status_dokter, $this->nomor_surat_dokter);

                if (! $validasi['valid']) {
                    $this->dispatch('toast', type: 'error', message: $validasi['pesan']);

                    return;
                }
                break;
            case 'melahirkan':
                $validasi = $cutiMelahirkanService->validasiCutiMelahirkan($this->lama_hari);

                if (! $validasi['valid']) {
                    $this->dispatch('toast', type: 'error', message: $validasi['pesan']);

                    return;
                }
                break;
            case 'alasan_penting':
                $validasi = $cutiAlasanPentingService->validasiCutiAlasanPenting($this->lama_hari, $this->alasan);

                if (! $validasi['valid']) {
                    $this->dispatch('toast', type: 'error', message: $validasi['pesan']);

                    return;
                }
                break;
            case 'luar_tanggungan':
                $validasi = $cutiLuarTanggunganService->validasiCutiLuarTanggungan($this->lama_hari, $this->alasan_luar_tanggungan);

                if (! $validasi['valid']) {
                    $this->dispatch('toast', type: 'error', message: $validasi['pesan']);

                    return;
                }
                break;
            default: // tahunan
                $validasi = $cutiTahunanService->validasiJumlahHari($pegawai, $this->lama_hari);

                if (! $validasi['valid']) {
                    $this->dispatch('toast', type: 'error', message: $validasi['pesan']);

                    return;
                }

                $cutiTahunanService->prosesPengambilanCuti($pegawai, $this->lama_hari);
                break;
        }

        $validated = $this->validate([
            'pegawai_id' => ['required', 'exists:pegawais,id'],
            'nomor_surat' => ['required', 'string', 'max:255'],
            'jenis_cuti' => ['required', 'in:tahunan,besar,sakit,melahirkan,alasan_penting,luar_tanggungan'],
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
            // Fields untuk Cuti Sakit
            'status_dokter' => ['nullable', 'string', 'in:swasta,pemerintah'],
            'nama_dokter' => ['nullable', 'string', 'max:255'],
            'nomor_surat_dokter' => ['nullable', 'string', 'max:255'],
            // Fields untuk Cuti Melahirkan
            'jenis_melahirkan' => ['nullable', 'string', 'in:normal,caesar'],
            'tanggal_perkiraan_lahir' => ['nullable', 'date'],
            // Fields untuk Cuti Luar Tanggungan
            'tanpa_gaji' => ['boolean'],
            'alasan_luar_tanggungan' => ['nullable', 'string'],
        ]);

        // Buat cuti
        $cutiService->create($validated);

        $this->dispatch('toast', type: 'success', message: 'Data cuti berhasil disimpan!');

        return $this->redirect('/admin/cutis');
    }

    public function render()
    {
        return view('livewire.admin.cuti.create');
    }
}
