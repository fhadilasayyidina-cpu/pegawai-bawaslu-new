<?php

namespace App\Services\Kgb;

use Illuminate\Support\Collection;
use Rap2hpoutre\FastExcel\FastExcel;

class ExportKgbService
{
    /**
     * Export data KGB ke file Excel
     *
     * @param  Collection  $kgbList  Data pegawai yang akan KGB
     * @param  string  $filePath  Path file tujuan
     */
    public function export(Collection $kgbList, string $filePath): string
    {
        $data = $kgbList->map(fn ($item) => [
            'NIP' => $item->nip_baru,
            'Nama' => $item->nama,
            'Email' => $item->email ?? '-',
            'Email Gov' => $item->email_gov ?? '-',
            'KGB Terakhir' => $item->tgl_kgb_terakhir->format('d/m/Y'),
            'KGB Berikutnya' => $item->next_kgb_date->format('d/m/Y'),
            'Status' => $item->jenis_pegawai,
            'Unit Kerja' => $item->unit_kerja ?? '-',
            'Kabupaten/Kota' => $item->kab_kota ?? '-',
        ]);

        return (new FastExcel($data))->export($filePath);
    }
}
