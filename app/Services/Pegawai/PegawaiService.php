<?php

namespace App\Services\Pegawai;

use App\Models\Pegawai;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\User;
use App\Cache\PegawaiCache;
use Illuminate\Support\Facades\Cache;

class PegawaiService
{
    public function getAllPegawai(
        ?string $search = null,
        ?string $kabKota = null,
        ?string $rangeUmur = null,
        ?string $jenisKelamin = null,
        ?string $agama = null,
        array $with = []
    ): LengthAwarePaginator {
        $query = Pegawai::query();

        // Auto-filter by access_scope for non-admin users
        if (auth()->check() && auth()->user()->role->value !== 'admin' && auth()->user()->access_scope) {
            $query->where('kab_kota', auth()->user()->access_scope);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                    ->orWhere('nip_baru', 'like', '%' . $search . '%')
                    ->orWhere('nik', 'like', '%' . $search . '%')
                    ->orWhere('jabatan_nama', 'like', '%' . $search . '%');
            });
        }

        if ($kabKota) {
            $query->where('kab_kota', $kabKota);
        }

        if ($rangeUmur) {
            $query->where('range_umur', $rangeUmur);
        }

        if ($jenisKelamin) {
            $query->where('jenis_kelamin', $jenisKelamin);
        }

        if ($agama) {
            $query->where('agama_nama', $agama);
        }

        return $query->orderBy('nama', 'asc')->paginate(10);
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
        return Cache::rememberForever(
            PegawaiCache::kabKotaOptions(),
            fn() => Pegawai::query()
                ->toBase() // ⬅️ ini kuncinya
                ->selectRaw('kab_kota as id, kab_kota as name')
                ->whereNotNull('kab_kota')
                ->where('kab_kota', '!=', '')
                ->distinct()
                ->orderBy('kab_kota')
                ->get()
        );
    }

    public function getAllForSelect(): array
    {
        return Pegawai::query()
            ->orderBy('nama')
            ->get()
            ->map(fn($p) => [
                'id' => $p->id,
                'name' => "{$p->nama} - {$p->nip_baru}",
            ])
            ->toArray();
    }

    public function getRangeUmurOptions()
    {
        return Pegawai::query()
            ->toBase()
            ->selectRaw('range_umur as id, range_umur as name')
            ->whereNotNull('range_umur')
            ->where('range_umur', '!=', '')
            ->distinct()
            ->orderBy('range_umur')
            ->get();
    }

    public function getJenisKelaminOptions()
    {
        return Pegawai::query()
            ->toBase()
            ->selectRaw('jenis_kelamin as id, jenis_kelamin as name')
            ->whereNotNull('jenis_kelamin')
            ->where('jenis_kelamin', '!=', '')
            ->distinct()
            ->orderBy('jenis_kelamin')
            ->get();
    }

    public function getAgamaOptions()
    {
        return Pegawai::query()
            ->toBase()
            ->selectRaw('agama_nama as id, agama_nama as name')
            ->whereNotNull('agama_nama')
            ->where('agama_nama', '!=', '')
            ->distinct()
            ->orderBy('agama_nama')
            ->get();
    }

    public function getUlangTahunHariIni()
    {
        $pegawais = Cache::remember(PegawaiCache::ulangTahunHariIni(), now()->endOfDay(), function () {
            return Pegawai::select(
                'id',
                'nama',
                'nik',
                'unit_kerja',
                'tgl_lahir'
            )->whereMonth('tgl_lahir', '=', now()->month, 'and')
                ->whereDay('tgl_lahir', '=', now()->day, 'and')
                ->orderBy('nama')->get();
        });

        return $pegawais;
    }
}
