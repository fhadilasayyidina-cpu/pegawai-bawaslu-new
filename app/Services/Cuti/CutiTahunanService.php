<?php

namespace App\Services\Cuti;

use App\Models\Cuti;
use App\Models\Pegawai;
use Carbon\Carbon;

class CutiTahunanService
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
     * Cek apakah pegawai sudah mengambil cuti besar di tahun ini.
     */
    public function cekCutiBesarDiTahunIni(Pegawai $pegawai): bool
    {
        $tahunIni = Carbon::now()->year;

        return Cuti::where('pegawai_id', $pegawai->id)
            ->where('jenis_cuti', 'besar')
            ->whereYear('tanggal_mulai', $tahunIni)
            ->exists();
    }

    /**
     * Cek apakah pegawai berhak cuti tahunan.
     * - Harus PNS
     * - Masa kerja minimal 1 tahun (12 bulan)
     * - Tidak ada cuti besar di tahun yang sama
     */
    public function cekKelayakanCuti(Pegawai $pegawai): array
    {
        $result = [
            'layak' => true,
            'alasan' => [],
        ];

        // Cek status PNS
        if ($pegawai->status_kepegwaian !== 'PNS') {
            $result['layak'] = false;
            $result['alasan'][] = 'Hanya pegawai dengan status PNS yang dapat mengambil cuti tahunan.';
        }

        // Cek masa kerja
        $masaKerjaBulan = $this->hitungMasaKerjaBulan($pegawai);
        if ($masaKerjaBulan < 12) {
            $result['layak'] = false;
            $result['alasan'][] = "Masa kerja kurang dari 1 tahun ({$masaKerjaBulan} bulan).";
        }

        // Cek cuti besar di tahun yang sama
        if ($this->cekCutiBesarDiTahunIni($pegawai)) {
            $result['layak'] = false;
            $result['alasan'][] = 'Tidak dapat mengambil cuti tahunan jika sudah mengambil cuti besar pada tahun yang sama.';
        }

        return $result;
    }

    /**
     * Hitung total jatah cuti tersedia.
     * Jatah dasar (12) + sisa tahun lalu (maks 6) + sisa 2 tahun lalu (maks 6) = maks 24.
     */
    public function hitungJatahTersedia(Pegawai $pegawai): int
    {
        $jatahDasar = $pegawai->sisa_cuti_tahun_berjalan ?? 12;
        $sisaTahunLalu = min($pegawai->sisa_cuti_tahun_lalu ?? 0, 6);
        $sisaDuaTahunLalu = min($pegawai->sisa_cuti_dua_tahun_lalu ?? 0, 6);

        $total = $jatahDasar + $sisaTahunLalu + $sisaDuaTahunLalu;

        return min($total, 24);
    }

    /**
     * Validasi apakah jumlah hari cuti yang diminta tersedia.
     */
    public function validasiJumlahHari(Pegawai $pegawai, int $jumlahHari): array
    {
        $kelayakan = $this->cekKelayakanCuti($pegawai);

        if (! $kelayakan['layak']) {
            return [
                'valid' => false,
                'pesan' => implode(' ', $kelayakan['alasan']),
            ];
        }

        $jatahTersedia = $this->hitungJatahTersedia($pegawai);

        if ($jumlahHari > $jatahTersedia) {
            return [
                'valid' => false,
                'pesan' => "Jumlah hari yang diminta ({$jumlahHari}) melebihi jatah tersedia ({$jatahTersedia} hari).",
            ];
        }

        return [
            'valid' => true,
            'pesan' => 'Cuti dapat disetujui.',
            'jatah_tersedia' => $jatahTersedia,
        ];
    }

    /**
     * Proses pengambilan cuti dan update sisa jatah.
     */
    public function prosesPengambilanCuti(Pegawai $pegawai, int $jumlahHari): array
    {
        $validasi = $this->validasiJumlahHari($pegawai, $jumlahHari);

        if (! $validasi['valid']) {
            return $validasi;
        }

        // Kurangi dari jatah tahun berjalan dulu
        $sisa = $jumlahHari;

        // Prioritas: tahun berjalan -> tahun lalu -> dua tahun lalu
        if ($pegawai->sisa_cuti_tahun_berjalan > 0) {
            $ambilDariBerjalan = min($sisa, $pegawai->sisa_cuti_tahun_berjalan);
            $pegawai->sisa_cuti_tahun_berjalan -= $ambilDariBerjalan;
            $sisa -= $ambilDariBerjalan;
        }

        if ($sisa > 0 && $pegawai->sisa_cuti_tahun_lalu > 0) {
            $ambilDariTahunLalu = min($sisa, $pegawai->sisa_cuti_tahun_lalu);
            $pegawai->sisa_cuti_tahun_lalu -= $ambilDariTahunLalu;
            $sisa -= $ambilDariTahunLalu;
        }

        if ($sisa > 0 && $pegawai->sisa_cuti_dua_tahun_lalu > 0) {
            $ambilDariDuaTahunLalu = min($sisa, $pegawai->sisa_cuti_dua_tahun_lalu);
            $pegawai->sisa_cuti_dua_tahun_lalu -= $ambilDariDuaTahunLalu;
        }

        $pegawai->save();

        return [
            'valid' => true,
            'pesan' => 'Cuti berhasil dicatat.',
            'sisa_jatah' => $this->hitungJatahTersedia($pegawai),
        ];
    }

    /**
     * Reset jatah cuti saat tahun berganti (dijalankan via job/scheduler).
     */
    public function rotasiJatahTahunan(Pegawai $pegawai): void
    {
        // Sisa dua tahun lalu di-reset ke 0 (hangus)
        $pegawai->sisa_cuti_dua_tahun_lalu = 0;

        // Sisa tahun lalu menjadi sisa dua tahun lalu
        $pegawai->sisa_cuti_dua_tahun_lalu = min($pegawai->sisa_cuti_tahun_lalu ?? 0, 6);

        // Sisa tahun berjalan menjadi sisa tahun lalu
        $pegawai->sisa_cuti_tahun_lalu = min($pegawai->sisa_cuti_tahun_berjalan ?? 12, 6);

        // Reset jatah tahun berjalan
        $pegawai->sisa_cuti_tahun_berjalan = 12;

        $pegawai->save();
    }

    /**
     * Restore jatah cuti ketika cuti dihapus.
     */
    public function restoreJatahCuti(Cuti $cuti): void
    {
        $pegawai = $cuti->pegawai;

        // Kembalikan jatah ke tahun berjalan
        $pegawai->sisa_cuti_tahun_berjalan += $cuti->lama_hari;

        $pegawai->save();
    }

    /**
     * Adjust jatah cuti ketika cuti diedit (lama_hari berubah).
     */
    public function adjustJatahCuti(Cuti $cuti, int $lamaHariBaru): array
    {
        $pegawai = $cuti->pegawai;
        $selisih = $lamaHariBaru - $cuti->lama_hari;

        if ($selisih === 0) {
            return [
                'valid' => true,
                'pesan' => 'Tidak ada perubahan jumlah hari.',
            ];
        }

        // Jika menambah hari
        if ($selisih > 0) {
            $validasi = $this->validasiJumlahHari($pegawai, $selisih);

            if (! $validasi['valid']) {
                return $validasi;
            }

            // Kurangi jatah
            $this->kurangiJatah($pegawai, $selisih);
        } else {
            // Jika mengurangi hari, kembalikan jatah
            $pegawai->sisa_cuti_tahun_berjalan += abs($selisih);
            $pegawai->save();
        }

        return [
            'valid' => true,
            'pesan' => 'Jatah cuti berhasil disesuaikan.',
            'sisa_jatah' => $this->hitungJatahTersedia($pegawai),
        ];
    }

    /**
     * Kurangi jatah cuti (helper method).
     */
    private function kurangiJatah(Pegawai $pegawai, int $jumlahHari): void
    {
        $sisa = $jumlahHari;

        if ($pegawai->sisa_cuti_tahun_berjalan > 0) {
            $ambilDariBerjalan = min($sisa, $pegawai->sisa_cuti_tahun_berjalan);
            $pegawai->sisa_cuti_tahun_berjalan -= $ambilDariBerjalan;
            $sisa -= $ambilDariBerjalan;
        }

        if ($sisa > 0 && $pegawai->sisa_cuti_tahun_lalu > 0) {
            $ambilDariTahunLalu = min($sisa, $pegawai->sisa_cuti_tahun_lalu);
            $pegawai->sisa_cuti_tahun_lalu -= $ambilDariTahunLalu;
            $sisa -= $ambilDariTahunLalu;
        }

        if ($sisa > 0 && $pegawai->sisa_cuti_dua_tahun_lalu > 0) {
            $ambilDariDuaTahunLalu = min($sisa, $pegawai->sisa_cuti_dua_tahun_lalu);
            $pegawai->sisa_cuti_dua_tahun_lalu -= $ambilDariDuaTahunLalu;
        }

        $pegawai->save();
    }
}
