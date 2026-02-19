<?php

namespace App\Models;

use App\Enums\PimpinanJabatan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Pimpinan extends Model
{
    protected $fillable = ['nama', 'jabatan', 'kab_kota', 'email', 'no_hp', 'foto'];

    protected function casts(): array
    {
        return [
            'jabatan' => PimpinanJabatan::class,
        ];
    }

    public function getFotoUrlAttribute(): string
    {
        if ($this->foto) {
            return Storage::url($this->foto);
        }

        return '';
    }
}
