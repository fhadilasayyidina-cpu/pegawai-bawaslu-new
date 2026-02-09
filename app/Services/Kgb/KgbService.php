<?php

namespace App\Services\Kgb;

use App\Models\Pegawai;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class KgbService
{
    /**
     * Get pegawai yang akan KGB dalam rentang bulan tertentu
     *
     * @param  int|null  $monthsAhead  Jumlah bulan ke depan (default: 6 bulan)
     * @param  string|null  $kabKota  Filter kabupaten/kota
     */
    public function getUpcomingKgb(?int $monthsAhead = 6, ?string $kabKota = null): Collection
    {
        $query = Pegawai::query()
            ->whereNotNull('tgl_kgb_terakhir')
            ->where('status_kepegwaian', 'Aktif');

        if ($kabKota) {
            $query->where('kab_kota', $kabKota);
        }

        $pegawais = $query->get();

        // If monthsAhead is 0, show all employees (no date filter)
        if ($monthsAhead === 0) {
            return $pegawais->map(function ($pegawai) {
                $lastKgb = Carbon::parse($pegawai->tgl_kgb_terakhir);
                $nextKgbDate = $lastKgb->copy()->addYears(2);
                $daysUntil = Carbon::now()->diffInDays($nextKgbDate, false);

                return (object) [
                    'id' => $pegawai->id,
                    'nama' => $pegawai->nama,
                    'nip_baru' => $pegawai->nip_baru,
                    'tgl_kgb_terakhir' => $lastKgb,
                    'next_kgb_date' => $nextKgbDate,
                    'days_until_kgb' => $daysUntil,
                    'status_kepegwaian' => $pegawai->status_kepegwaian,
                    'jenis_pegawai' => $pegawai->jenis_pegawai,
                    'unit_kerja' => $pegawai->unit_kerja,
                    'kab_kota' => $pegawai->kab_kota,
                ];
            })->sortBy('next_kgb_date')->values();
        }

        // Original filtering logic for specific month ranges
        $now = Carbon::now();
        $endDate = $now->copy()->addMonths($monthsAhead);

        // Filter pegawai yang KGB-nya jatuh dalam rentang waktu atau sudah lewat
        return $pegawais->filter(function ($pegawai) use ($endDate) {
            $nextKgbDate = Carbon::parse($pegawai->tgl_kgb_terakhir)->copy()->addYears(2);

            // Include KGBs that are upcoming within the timeframe OR already passed (overdue)
            return $nextKgbDate->lte($endDate);
        })->map(function ($pegawai) {
            $lastKgb = Carbon::parse($pegawai->tgl_kgb_terakhir);
            $nextKgbDate = $lastKgb->copy()->addYears(2);
            $daysUntil = Carbon::now()->diffInDays($nextKgbDate, false);

            return (object) [
                'id' => $pegawai->id,
                'nama' => $pegawai->nama,
                'nip_baru' => $pegawai->nip_baru,
                'tgl_kgb_terakhir' => $lastKgb,
                'next_kgb_date' => $nextKgbDate,
                'days_until_kgb' => $daysUntil,
                'status_kepegwaian' => $pegawai->status_kepegwaian,
                'jenis_pegawai' => $pegawai->jenis_pegawai,
                'unit_kerja' => $pegawai->unit_kerja,
                'kab_kota' => $pegawai->kab_kota,
            ];
        })->sortBy('next_kgb_date')->values();
    }

    /**
     * Get statistik KGB
     */
    public function getStatistics(?int $monthsAhead = 6, ?string $kabKota = null): array
    {
        $kgbList = $this->getUpcomingKgb($monthsAhead, $kabKota);

        $now = Carbon::now();

        $sudahLewat = $kgbList->filter(fn ($p) => $p->days_until_kgb < 0)->count();
        $bulanIni = $kgbList->filter(fn ($p) => $p->next_kgb_date->isSameMonth($now))->count();
        $bulanDepan = $kgbList->filter(fn ($p) => $p->next_kgb_date->isSameMonth($now->copy()->addMonth()))->count();
        $total = $kgbList->count();

        return [
            'total' => $total,
            'sudah_lewat' => $sudahLewat,
            'bulan_ini' => $bulanIni,
            'bulan_depan' => $bulanDepan,
            'lainnya' => $total - $bulanIni - $bulanDepan - $sudahLewat,
        ];
    }
}
