<?php

namespace App\Cache;

use App\Models\Pegawai;
use Illuminate\Support\Facades\Cache;

class PegawaiCache
{
    public static function ulangTahunHariIni(): string
    {
        return 'pegawai:ulang-tahun:' . now()->toDateString();
    }

    public static function kabKotaOptions(): string
    {
        return 'pegawai:kab-kota:options';
    }

    public static function dashboardStats(?string $kabKota = null): string
    {
        return 'dashboard_stats_' . ($kabKota ?: 'semua');
    }

    public static function clearAllPegawaiCache(): void
    {
        cache()->forget(self::ulangTahunHariIni());
        cache()->forget(self::kabKotaOptions());


        Pegawai::select('kab_kota')->distinct()->pluck('kab_kota')->each(function ($kabKota) {
            Cache::forget(self::dashboardStats($kabKota));
        });
    }
}
