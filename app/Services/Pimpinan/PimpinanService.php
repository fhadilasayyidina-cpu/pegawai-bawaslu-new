<?php

namespace App\Services\Pimpinan;

use App\Models\Pimpinan;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PimpinanService
{
    public function getAllPimpinan(?string $search = null, ?string $kabKota = null): LengthAwarePaginator
    {
        $query = Pimpinan::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%')
                    ->orWhere('no_hp', 'like', '%'.$search.'%');
            });
        }

        if ($kabKota) {
            $query->where('kab_kota', $kabKota);
        }

        return $query->orderBy('nama')->paginate(10);
    }

    public function getPimpinanById(int $id): ?Pimpinan
    {
        return Pimpinan::find($id);
    }

    public function deletePimpinan(int $id): bool
    {
        $pimpinan = Pimpinan::find($id);
        if ($pimpinan) {
            return $pimpinan->delete();
        }

        return false;
    }

    public function getKabKota()
    {
        return Pimpinan::query()
            ->toBase()
            ->selectRaw('kab_kota as id, kab_kota as name')
            ->whereNotNull('kab_kota')
            ->where('kab_kota', '!=', '')
            ->distinct()
            ->orderBy('kab_kota')
            ->get();
    }

    public function createPimpinan(array $data): Pimpinan
    {
        return Pimpinan::create($data);
    }

    public function updatePimpinan(int $id, array $data): ?Pimpinan
    {
        $pimpinan = Pimpinan::find($id);

        if ($pimpinan) {
            $pimpinan->update($data);

            return $pimpinan;
        }

        return null;
    }
}
