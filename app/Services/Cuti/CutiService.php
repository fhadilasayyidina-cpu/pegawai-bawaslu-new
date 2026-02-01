<?php

namespace App\Services\Cuti;

use App\Models\Cuti;
use Illuminate\Database\Eloquent\Collection;

class CutiService
{
    public function getAll(array $filters = []): Collection
    {
        $query = Cuti::with('pegawai');

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                    ->orWhereHas('pegawai', function ($pq) use ($search) {
                        $pq->where('nama', 'like', "%{$search}%")
                            ->orWhere('nip_baru', 'like', "%{$search}%");
                    });
            });
        }

        if (isset($filters['pegawai_id'])) {
            $query->where('pegawai_id', $filters['pegawai_id']);
        }

        return $query->latest()->get();
    }

    public function getById(int $id): Cuti
    {
        return Cuti::with('pegawai')->findOrFail($id);
    }

    public function create(array $data): Cuti
    {
        return Cuti::create($data);
    }

    public function update(int $id, array $data): Cuti
    {
        $cuti = $this->getById($id);
        $cuti->update($data);

        return $cuti;
    }

    public function delete(int $id): void
    {
        $cuti = $this->getById($id);
        $cuti->delete();
    }

    public function getByPegawaiId(int $pegawaiId): Collection
    {
        return Cuti::where('pegawai_id', $pegawaiId)
            ->latest()
            ->get();
    }
}
