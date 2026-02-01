<?php

namespace App\Models;

use App\Enums\PimpinanJabatan;
use Illuminate\Database\Eloquent\Model;

class Pimpinan extends Model
{
    protected $fillable = ['nama', 'jabatan', 'kab_kota', 'email', 'no_hp'];

    protected function casts(): array
    {
        return [
            'jabatan' => PimpinanJabatan::class,
        ];
    }
}
