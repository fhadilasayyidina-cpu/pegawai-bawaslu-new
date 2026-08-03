<?php

namespace App\Enums;

enum StatusAbsensi: string
{
    case HADIR = 'Hadir';
    case IZIN = 'Izin';
    case SAKIT = 'Sakit';
    case CUTI = 'Cuti';
    case BOLOS = 'Bolos';
}
