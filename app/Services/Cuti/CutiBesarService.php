<?php

namespace App\Services\Cuti;

use App\Models\Cuti;
use App\Models\Pegawai;
use Carbon\Carbon;

class CutiBesarService
{
    /**
     * Hitung masa kerja dalam bulan dari tmt_cpns.
     */
    public function hitungMasaKerjaBulan(Pegawai $pegawai): int
    {
        if (! $pegawai->tmt_cpns) {
            return 0;
        }

        return Carbon::parse($pegawai->tmt_cpns)->diffInMonths(Carbon::now());
    }

    /**
     * Cek apakah pegawai berhak cuti besar.
     * - Harus PNS Organik
     * - Masa kerja minimal 5 tahun (60 bulan)
     * - Kuota maksimal 2 kali
     * - Tidak ada cuti tahunan di tahun yang sama
     */
    public function cekKelayakanCutiBesar(Pegawai $pegawai): array
    {
        $result = [
            'layak' => true,
            'alasan' => [],
        ];

        // Cek PNS Organik
        if (! $this->isPNSOrganik($pegawai)) {
            $result['layak'] = false;
            $result['alasan'][] = 'Hanya PNS Organik yang berhak atas cuti besar.';

            return $result;
        }

        // Cek masa kerja (minimal 5 tahun = 60 bulan)
        $masaKerjaBulan = $this->hitungMasaKerjaBulan($pegawai);
        if ($masaKerjaBulan < 60) {
            $result['layak'] = false;
            $result['alasan'][] = "Masa kerja kurang dari 5 tahun ({$masaKerjaBulan} bulan).";
        }

        // Cek kuota (maksimal 2 kali)
        $sisaKuota = $this->hitungSisaKuota($pegawai);
        if ($sisaKuota <= 0) {
            $result['layak'] = false;
            $result['alasan'][] = 'Kuota cuti besar sudah habis (maksimal 2 kali).';
        }

        // Cek cuti tahunan di tahun yang sama
        if ($this->cekCutiTahunanDiTahunIni($pegawai)) {
            $result['layak'] = false;
            $result['alasan'][] = 'Tidak dapat mengambil cuti besar jika sudah mengambil cuti tahunan pada tahun yang sama.';
        }

        return $result;
    }

    /**
     * Cek apakah pegawai adalah PNS (Organik atau DPK) yang berhak atas cuti.
     * PPPK tidak dianggap PNS untuk keperluan cuti.
     */
    public function isPNSOrganik(Pegawai $pegawai): bool
    {
        $jenis = strtolower($pegawai->jenis_pegawai ?? '');

        // Cek mengandung "pns" dan bukan "pppk"
        return str_contains($jenis, 'pns') && ! str_contains($jenis, 'pppk');
    }

    /**
     * Cek apakah pegawai sudah mengambil cuti tahunan di tahun ini.
     */
    public function cekCutiTahunanDiTahunIni(Pegawai $pegawai): bool
    {
        $tahunIni = Carbon::now()->year;

        return Cuti::where('pegawai_id', $pegawai->id)
            ->where('jenis_cuti', 'tahunan')
            ->whereYear('tanggal_mulai', $tahunIni)
            ->exists();
    }

    /**
     * Hitung sisa kuota cuti besar (2 - jumlah yang sudah diambil).
     */
    public function hitungSisaKuota(Pegawai $pegawai): int
    {
        return max(0, 2 - ($pegawai->jumlah_cuti_besar_diambil ?? 0));
    }

    /**
     * Validasi durasi cuti besar (maksimal 90 hari / 3 bulan).
     */
    public function validasiDurasi(int $jumlahHari): array
    {
        if ($jumlahHari > 90) {
            return [
                'valid' => false,
                'pesan' => "Durasi cuti besar melebihi batas maksimal 3 bulan (90 hari). Permintaan: {$jumlahHari} hari.",
            ];
        }

        return [
            'valid' => true,
            'pesan' => 'Durasi cuti besar valid.',
        ];
    }

    /**
     * Validasi apakah cuti besar dapat diambil.
     */
    public function validasiCutiBesar(Pegawai $pegawai, int $jumlahHari): array
    {
        // Cek kelayakan
        $kelayakan = $this->cekKelayakanCutiBesar($pegawai);

        if (! $kelayakan['layak']) {
            return [
                'valid' => false,
                'pesan' => implode(' ', $kelayakan['alasan']),
            ];
        }

        // Validasi durasi
        $validasiDurasi = $this->validasiDurasi($jumlahHari);

        if (! $validasiDurasi['valid']) {
            return $validasiDurasi;
        }

        return [
            'valid' => true,
            'pesan' => 'Cuti besar dapat disetujui.',
        ];
    }

    /**
     * Proses pengambilan cuti besar dan update counter.
     */
    public function prosesPengambilanCutiBesar(Pegawai $pegawai, int $jumlahHari): array
    {
        $validasi = $this->validasiCutiBesar($pegawai, $jumlahHari);

        if (! $validasi['valid']) {
            return $validasi;
        }

        // Update counter dan tanggal terakhir
        $pegawai->jumlah_cuti_besar_diambil = ($pegawai->jumlah_cuti_besar_diambil ?? 0) + 1;
        $pegawai->tanggal_cuti_besar_terakhir = Carbon::now();
        $pegawai->save();

        return [
            'valid' => true,
            'pesan' => 'Cuti besar berhasil dicatat.',
            'sisa_kuota' => $this->hitungSisaKuota($pegawai),
        ];
    }

    /**
     * Restore kuota cuti besar ketika cuti dihapus.
     */
    public function restoreJatahCutiBesar(Cuti $cuti): void
    {
        $pegawai = $cuti->pegawai;

        // Kurangi counter (minimal 0)
        $pegawai->jumlah_cuti_besar_diambil = max(0, ($pegawai->jumlah_cuti_besar_diambil ?? 1) - 1);
        $pegawai->save();
    }

    /**
     * Get info lengkap tentang cuti besar pegawai.
     */
    public function getInfoCutiBesar(Pegawai $pegawai): array
    {
        $kelayakan = $this->cekKelayakanCutiBesar($pegawai);
        $sisaKuota = $this->hitungSisaKuota($pegawai);
        $masaKerjaBulan = $this->hitungMasaKerjaBulan($pegawai);
        $masaKerjaTahun = floor($masaKerjaBulan / 12);
        $adaCutiTahunan = $this->cekCutiTahunanDiTahunIni($pegawai);

        return [
            'layak' => $kelayakan['layak'],
            'alasan' => $kelayakan['alasan'] ?? [],
            'masa_kerja_bulan' => $masaKerjaBulan,
            'masa_kerja_tahun' => $masaKerjaTahun,
            'sisa_kuota' => $sisaKuota,
            'kuota_maksimal' => 2,
            'jumlah_sudah_diambil' => $pegawai->jumlah_cuti_besar_diambil ?? 0,
            'tanggal_cuti_besar_terakhir' => $pegawai->tanggal_cuti_besar_terakhir?->format('d F Y'),
            'ada_cuti_tahunan_tahun_ini' => $adaCutiTahunan,
        ];
    }
}
