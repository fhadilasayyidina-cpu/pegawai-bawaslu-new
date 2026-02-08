<?php

namespace App\Services\Absensi;

use App\Models\Absensi;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AbsensiService
{
    public function getAll(?string $search = null, ?string $tanggalMulai = null, ?string $tanggalAkhir = null, ?int $pegawaiId = null, ?string $status = null): LengthAwarePaginator
    {
        $query = Absensi::query()->with(['pegawai', 'createdBy']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('pegawai', function ($subQuery) use ($search) {
                    $subQuery->where('nama', 'like', '%'.$search.'%')
                        ->orWhere('nip_baru', 'like', '%'.$search.'%');
                });
            });
        }

        if ($tanggalMulai) {
            $query->where('tanggal', '>=', $tanggalMulai);
        }

        if ($tanggalAkhir) {
            $query->where('tanggal', '<=', $tanggalAkhir);
        }

        if ($pegawaiId) {
            $query->where('pegawai_id', $pegawaiId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        return $query->orderBy('tanggal', 'desc')->orderBy('created_at', 'desc')->paginate(10);
    }

    public function findById(int $id): ?Absensi
    {
        return Absensi::with(['pegawai', 'createdBy'])->find($id);
    }

    public function create(array $data): Absensi
    {
        return Absensi::create($data);
    }

    public function update(int $id, array $data): ?Absensi
    {
        $absensi = Absensi::find($id);
        if ($absensi) {
            $absensi->update($data);

            return $absensi->fresh();
        }

        return null;
    }

    public function delete(int $id): bool
    {
        $absensi = Absensi::find($id);
        if ($absensi) {
            return $absensi->delete();
        }

        return false;
    }

    public function getPegawaiOptions(): array
    {
        return \App\Models\Pegawai::query()
            ->orderBy('nama')
            ->get()
            ->map(fn ($p) => [
                'id' => $p->id,
                'name' => "{$p->nama} - {$p->nip_baru}",
            ])
            ->toArray();
    }
}
