<?php

namespace App\Support\Absensi;

use App\Enums\StatusAbsensi;

class AbsensiStatusHelper
{
    public static function determine(
        ?string $scanMasuk,
        ?string $scanPulang
    ): StatusAbsensi {
        return ($scanMasuk || $scanPulang)
            ? StatusAbsensi::HADIR
            : StatusAbsensi::TIDAK_HADIR;
    }
}
