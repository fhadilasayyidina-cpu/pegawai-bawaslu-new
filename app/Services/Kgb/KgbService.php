<?php

namespace App\Services\Kgb;

use App\Models\KgbRecord;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class KgbService
{
    /**
     * Get daftar inputan/riwayat KGB
     *
     * @param  int|null  $monthsAhead  Filter bulan ke depan (0 = Semua)
     * @param  string|null  $kabKota  Filter kabupaten/kota
     */
    public function getUpcomingKgb(?int $monthsAhead = 6, ?string $kabKota = null): Collection
    {
        $query = KgbRecord::query()
            ->with('pegawai')
            ->latest('tanggal_naskah')
            ->latest('id');

        if ($kabKota) {
            $query->whereHas('pegawai', function ($q) use ($kabKota) {
                $q->where('kab_kota', $kabKota);
            });
        }

        if ($monthsAhead !== null && $monthsAhead > 0) {
            $endDate = Carbon::now()->addMonths($monthsAhead);
            $query->where(function ($q) use ($endDate) {
                $q->where('tmt_baru', '<=', $endDate)
                    ->orWhere('next_kgb_date', '<=', $endDate);
            });
        }

        return $query->get();
    }

    /**
     * Get statistik KGB berdasarkan data KGB yang diinput
     */
    public function getStatistics(?int $monthsAhead = 6, ?string $kabKota = null): array
    {
        $query = KgbRecord::query();

        if ($kabKota) {
            $query->whereHas('pegawai', function ($q) use ($kabKota) {
                $q->where('kab_kota', $kabKota);
            });
        }

        $allKgb = $query->get();

        $now = Carbon::now();

        $total = $allKgb->count();
        $pns = $allKgb->where('jenis_kgb', 'PNS')->count();
        $pppk = $allKgb->where('jenis_kgb', 'PPPK')->count();

        $bulanIni = $allKgb->filter(function ($kgb) use ($now) {
            return ($kgb->tanggal_naskah && $kgb->tanggal_naskah->isSameMonth($now))
                || ($kgb->tmt_baru && $kgb->tmt_baru->isSameMonth($now));
        })->count();

        $bulanDepan = $allKgb->filter(function ($kgb) use ($now) {
            $nextMonth = $now->copy()->addMonth();

            return ($kgb->tmt_baru && $kgb->tmt_baru->isSameMonth($nextMonth))
                || ($kgb->next_kgb_date && $kgb->next_kgb_date->isSameMonth($nextMonth));
        })->count();

        return [
            'total' => $total,
            'pns' => $pns,
            'pppk' => $pppk,
            'bulan_ini' => $bulanIni,
            'bulan_depan' => $bulanDepan,
        ];
    }
}
