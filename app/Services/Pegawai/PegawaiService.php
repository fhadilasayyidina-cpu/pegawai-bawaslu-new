<?php

namespace App\Services\Pegawai;

use App\Models\Pegawai;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PegawaiService
{
    public function getAllPegawai(?string $search = null, ?string $kabKota = null): LengthAwarePaginator
    {
        $query = Pegawai::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%'.$search.'%')
                    ->orWhere('nip_baru', 'like', '%'.$search.'%')
                    ->orWhere('nik', 'like', '%'.$search.'%');
            });
        }

        if ($kabKota) {
            $query->where('kab_kota', $kabKota);
        }

        return $query->orderBy('nama')->paginate(10);
    }

    public function getPegawaiById(int $id): ?Pegawai
    {
        return Pegawai::find($id);
    }

    public function deletePegawai(int $id): bool
    {
        $pegawai = Pegawai::find($id);
        if ($pegawai) {
            return $pegawai->delete();
        }

        return false;
    }

    public function getKabKota()
    {
        return Pegawai::query()
            ->toBase() // ⬅️ ini kuncinya
            ->selectRaw('kab_kota as id, kab_kota as name')
            ->whereNotNull('kab_kota')
            ->where('kab_kota', '!=', '')
            ->distinct()
            ->orderBy('kab_kota')
            ->get();
    }
}
