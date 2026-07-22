<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class KgbController extends Controller
{
    public function generatePnsPdf(Request $request)
    {
        $pegawai = Pegawai::findOrFail($request->query('pegawai_id'));

        $pdf = Pdf::loadView('pdf.kgb-pns', [
            'pegawai' => $pegawai,
            'nomor_naskah' => $request->query('nomor_naskah'),
            'tanggal_naskah' => $request->query('tanggal_naskah'),
            'ibu_kota_provinsi' => $request->query('ibu_kota_provinsi'),
            'sk_pejabat' => $request->query('sk_pejabat'),
            'sk_tanggal' => $request->query('sk_tanggal'),
            'sk_nomor' => $request->query('sk_nomor'),
            'sk_tmt' => $request->query('sk_tmt'),
            'sk_mkg' => $request->query('sk_mkg'),
            'gaji_pokok_lama' => $request->query('gaji_pokok_lama'),
            'gaji_pokok_baru' => $request->query('gaji_pokok_baru'),
            'masa_kerja_baru' => $request->query('masa_kerja_baru'),
            'golongan_ruang_baru' => $request->query('golongan_ruang_baru'),
            'tmt_baru' => $request->query('tmt_baru'),
            'next_kgb_date' => $request->query('next_kgb_date'),
            'ttd_pengirim' => $request->query('ttd_pengirim'),
            'nama_kasek' => $request->query('nama_kasek'),
        ]);

        $pegawaiName = str_replace([' ', '/'], '-', strtolower($pegawai->nama));
        $filename = 'kgb-pns-'.$pegawaiName.'.pdf';

        return $pdf->stream($filename);
    }

    public function generatePppkPdf(Request $request)
    {
        $pegawai = Pegawai::findOrFail($request->query('pegawai_id'));

        $pdf = Pdf::loadView('pdf.kgb-pppk', [
            'pegawai' => $pegawai,
            'nomor_naskah' => $request->query('nomor_naskah'),
            'tanggal_naskah' => $request->query('tanggal_naskah'),
            'ibu_kota_provinsi' => $request->query('ibu_kota_provinsi'),
            'ni_pppk' => $request->query('ni_pppk'),
            'jabatan_golongan' => $request->query('jabatan_golongan'),
            'masa_perjanjian_kerja' => $request->query('masa_perjanjian_kerja'),
            'perpanjangan_perjanjian_kerja' => $request->query('perpanjangan_perjanjian_kerja', '-'),
            'unit_kerja' => $request->query('unit_kerja'),
            'gaji_lama' => $request->query('gaji_lama'),
            'sk_pejabat' => $request->query('sk_pejabat'),
            'sk_tanggal' => $request->query('sk_tanggal'),
            'sk_nomor' => $request->query('sk_nomor'),
            'sk_tmt' => $request->query('sk_tmt'),
            'sk_mkg' => $request->query('sk_mkg'),
            'gaji_baru' => $request->query('gaji_baru'),
            'masa_kerja_baru' => $request->query('masa_kerja_baru'),
            'tmt_baru' => $request->query('tmt_baru'),
            'ttd_pengirim' => $request->query('ttd_pengirim'),
            'nama_kasek' => $request->query('nama_kasek'),
        ]);

        $pegawaiName = str_replace([' ', '/'], '-', strtolower($pegawai->nama));
        $filename = 'kgb-pppk-'.$pegawaiName.'.pdf';

        return $pdf->stream($filename);
    }
}
