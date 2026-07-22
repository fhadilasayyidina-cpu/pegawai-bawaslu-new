<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KgbRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'pegawai_id',
        'created_by',
        'jenis_kgb',
        'nomor_naskah',
        'tanggal_naskah',
        'tmt_baru',
        'next_kgb_date',
        'data',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_naskah' => 'date',
            'tmt_baru' => 'date',
            'next_kgb_date' => 'date',
            'data' => 'array',
        ];
    }

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
