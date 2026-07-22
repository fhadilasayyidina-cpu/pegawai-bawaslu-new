<?php

namespace App\Services\Kgb;

use Illuminate\Support\Collection;
use Rap2hpoutre\FastExcel\FastExcel;

class ExportKgbService
{
    /**
     * Export data KGB ke file Excel
     *
     * @param  Collection  $kgbList  Data inputan KGB
     * @param  string  $filePath  Path file tujuan
     */
    public function export(Collection $kgbList, string $filePath): string
    {
        $data = $kgbList->map(fn ($item) => [
            'NIP' => $item->pegawai->nip_baru ?? $item->nip_baru ?? '-',
            'Nama' => $item->pegawai->nama ?? $item->nama ?? '-',
            'Jenis KGB' => $item->jenis_kgb ?? $item->jenis_pegawai ?? '-',
            'Nomor Naskah' => $item->nomor_naskah ?? '-',
            'Tanggal Naskah' => isset($item->tanggal_naskah) ? $item->tanggal_naskah->format('d/m/Y') : '-',
            'TMT Baru' => isset($item->tmt_baru) ? $item->tmt_baru->format('d/m/Y') : (isset($item->tgl_kgb_terakhir) ? $item->tgl_kgb_terakhir->format('d/m/Y') : '-'),
            'KGB Berikutnya' => isset($item->next_kgb_date) ? $item->next_kgb_date->format('d/m/Y') : '-',
            'Unit Kerja' => $item->pegawai->unit_kerja ?? $item->unit_kerja ?? '-',
            'Kabupaten/Kota' => $item->pegawai->kab_kota ?? $item->kab_kota ?? '-',
        ]);

        return (new FastExcel($data))->export($filePath);
    }
}
