<?php

namespace App\Livewire\Admin\Cuti;

use App\Models\Cuti;
use App\Services\Cuti\CutiAlasanPentingService;
use App\Services\Cuti\CutiBesarService;
use App\Services\Cuti\CutiLuarTanggunganService;
use App\Services\Cuti\CutiMelahirkanService;
use App\Services\Cuti\CutiSakitService;
use App\Services\Cuti\CutiService;
use App\Services\Cuti\CutiTahunanService;
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

    public array $breadcrumbs = [];

    public array $jatahCutiInfo = [];

    public function mount(
        int $id,
        CutiService $cutiService,
        CutiTahunanService $cutiTahunanService,
        CutiBesarService $cutiBesarService,
        CutiSakitService $cutiSakitService,
        CutiMelahirkanService $cutiMelahirkanService,
        CutiAlasanPentingService $cutiAlasanPentingService,
        CutiLuarTanggunganService $cutiLuarTanggunganService
    ): void {
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

        // Load fields untuk Cuti Sakit
        $this->status_dokter = $this->cuti->status_dokter;
        $this->nama_dokter = $this->cuti->nama_dokter;
        $this->nomor_surat_dokter = $this->cuti->nomor_surat_dokter;

        // Load fields untuk Cuti Melahirkan
        $this->jenis_melahirkan = $this->cuti->jenis_melahirkan;
        $this->tanggal_perkiraan_lahir = $this->cuti->tanggal_perkiraan_lahir?->format('Y-m-d');

        // Load fields untuk Cuti Luar Tanggungan
        $this->tanpa_gaji = $this->cuti->tanpa_gaji ?? false;
        $this->alasan_luar_tanggungan = $this->cuti->alasan_luar_tanggungan;

        $this->breadcrumbs = [
            ['label' => 'Admin', 'link' => '/admin'],
            ['label' => 'Cuti', 'link' => '/admin/cutis'],
            ['label' => 'Edit', 'link' => '#'],
        ];

        // Load jatah cuti info
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
        $pegawai = $this->cuti->pegawai;

        switch ($this->jenis_cuti) {
            case 'besar':
                $this->jatahCutiInfo = $besarService->getInfoCutiBesar($pegawai);
                $this->jatahCutiInfo['lama_hari_sekarang'] = $this->cuti->lama_hari;
                break;
            case 'sakit':
                $this->jatahCutiInfo = $sakitService->getInfoCutiSakit($pegawai);
                $this->jatahCutiInfo['lama_hari_sekarang'] = $this->cuti->lama_hari;
                break;
            case 'melahirkan':
                $this->jatahCutiInfo = $melahirkanService->getInfoCutiMelahirkan($pegawai);
                $this->jatahCutiInfo['lama_hari_sekarang'] = $this->cuti->lama_hari;
                break;
            case 'alasan_penting':
                $this->jatahCutiInfo = $alasanPentingService->getInfoCutiAlasanPenting($pegawai);
                $this->jatahCutiInfo['lama_hari_sekarang'] = $this->cuti->lama_hari;
                break;
            case 'luar_tanggungan':
                $this->jatahCutiInfo = $luarTanggunganService->getInfoCutiLuarTanggungan($pegawai);
                $this->jatahCutiInfo['lama_hari_sekarang'] = $this->cuti->lama_hari;
                break;
            default: // tahunan
                $kelayakan = $tahunanService->cekKelayakanCuti($pegawai);

                // Hitung jatah tersedia SAAT INI (termasuk yang sedang dipakai cuti ini)
                $jatahDasar = $pegawai->sisa_cuti_tahun_berjalan ?? 12;
                $sisaTahunLalu = min($pegawai->sisa_cuti_tahun_lalu ?? 0, 6);
                $sisaDuaTahunLalu = min($pegawai->sisa_cuti_dua_tahun_lalu ?? 0, 6);

                $totalTersedia = min($jatahDasar + $sisaTahunLalu + $sisaDuaTahunLalu, 24);

                $this->jatahCutiInfo = [
                    'layak' => $kelayakan['layak'],
                    'jatah_tersedia' => $totalTersedia,
                    'lama_hari_sekarang' => $this->cuti->lama_hari,
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

    private function calculateLamaHari(): void
    {
        if ($this->tanggal_mulai && $this->tanggal_selesai) {
            $start = \Illuminate\Support\Carbon::parse($this->tanggal_mulai);
            $end = \Illuminate\Support\Carbon::parse($this->tanggal_selesai);
            $this->lama_hari = $start->diffInDays($end) + 1;
        }
    }

    public function update(
        CutiService $cutiService,
        CutiTahunanService $cutiTahunanService,
        CutiBesarService $cutiBesarService,
        CutiSakitService $cutiSakitService,
        CutiMelahirkanService $cutiMelahirkanService,
        CutiAlasanPentingService $cutiAlasanPentingService,
        CutiLuarTanggunganService $cutiLuarTanggunganService
    ) {
        $lamaHariLama = $this->cuti->lama_hari;
        $lamaHariBaru = $this->lama_hari;

        // Validasi berdasarkan jenis cuti jika lama_hari berubah
        if ($lamaHariBaru !== $lamaHariLama) {
            switch ($this->jenis_cuti) {
                case 'besar':
                    $validasi = $cutiBesarService->validasiDurasi($lamaHariBaru);

                    if (! $validasi['valid']) {
                        $this->dispatch('toast', type: 'error', message: $validasi['pesan']);

                        return;
                    }
                    break;
                case 'sakit':
                    $validasi = $cutiSakitService->validasiCutiSakit($lamaHariBaru, $this->status_dokter, $this->nomor_surat_dokter);

                    if (! $validasi['valid']) {
                        $this->dispatch('toast', type: 'error', message: $validasi['pesan']);

                        return;
                    }
                    break;
                case 'melahirkan':
                    $validasi = $cutiMelahirkanService->validasiCutiMelahirkan($lamaHariBaru);

                    if (! $validasi['valid']) {
                        $this->dispatch('toast', type: 'error', message: $validasi['pesan']);

                        return;
                    }
                    break;
                case 'alasan_penting':
                    $validasi = $cutiAlasanPentingService->validasiCutiAlasanPenting($lamaHariBaru, $this->alasan);

                    if (! $validasi['valid']) {
                        $this->dispatch('toast', type: 'error', message: $validasi['pesan']);

                        return;
                    }
                    break;
                case 'luar_tanggungan':
                    $validasi = $cutiLuarTanggunganService->validasiCutiLuarTanggungan($lamaHariBaru, $this->alasan_luar_tanggungan);

                    if (! $validasi['valid']) {
                        $this->dispatch('toast', type: 'error', message: $validasi['pesan']);

                        return;
                    }
                    break;
                default: // tahunan
                    $adjust = $cutiTahunanService->adjustJatahCuti($this->cuti, $lamaHariBaru);

                    if (! $adjust['valid']) {
                        $this->dispatch('toast', type: 'error', message: $adjust['pesan']);

                        return;
                    }
                    break;
            }
        }

        $validated = $this->validate([
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

        $cutiService->update($this->cuti->id, $validated);

        $this->dispatch('toast', type: 'success', message: 'Data cuti berhasil diperbarui!');

        return $this->redirect('/admin/cutis/'.$this->cuti->id);
    }

    public function render()
    {
        return view('livewire.admin.cuti.edit');
    }
}
