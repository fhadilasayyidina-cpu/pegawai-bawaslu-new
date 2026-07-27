<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use PhpOffice\PhpWord\TemplateProcessor;

class KgbController extends Controller
{
    private function formatTanggalIndo(?string $date): string
    {
        if (! $date) {
            return '-';
        }

        return Carbon::parse($date)->locale('id')->translatedFormat('j F Y');
    }

    public function generatePnsPdf(Request $request)
    {
        $pegawai = Pegawai::findOrFail($request->query('pegawai_id'));

        $templatePath = storage_path('app/templates/kgb-pns.docx');
        $template = new TemplateProcessor($templatePath);

        $pangkatGolongan = trim(($pegawai->pangkat ?? '-').' / '.($pegawai->gol_nama ?? '-'), ' /');

        $template->setValue('nomor_naskah', '${nomor_naskah}');
        $template->setValue('tanggal_naskah', '${tanggal_naskah}');
        $template->setValue('ibu_kota_provinsi', $request->query('ibu_kota_provinsi', 'Makassar'));
        $template->setValue('provinsi_title', 'Sulawesi Selatan');
        $template->setValue('nama', $pegawai->nama);
        $template->setValue('nip', $pegawai->nip_baru ?? '-');
        $template->setValue('pangkat_golongan', $pangkatGolongan);
        $template->setValue('kantor_tempat', $pegawai->unit_kerja ?? 'Bawaslu Provinsi Sulawesi Selatan');
        $template->setValue('gaji_pokok_lama', $request->query('gaji_pokok_lama', '-'));
        $template->setValue('sk_pejabat', $request->query('sk_pejabat', '-'));
        $template->setValue('sk_tanggal', $this->formatTanggalIndo($request->query('sk_tanggal')));
        $template->setValue('sk_nomor', $request->query('sk_nomor', '-'));
        $template->setValue('sk_tmt', $this->formatTanggalIndo($request->query('sk_tmt')));
        $template->setValue('sk_mkg', $request->query('sk_mkg', '-'));
        $template->setValue('gaji_pokok_baru', $request->query('gaji_pokok_baru', '-'));
        $template->setValue('masa_kerja_baru', $request->query('masa_kerja_baru', '-'));
        $template->setValue('golongan_ruang_baru', $request->query('golongan_ruang_baru', '-'));
        $template->setValue('tmt_baru', $this->formatTanggalIndo($request->query('tmt_baru')));
        $template->setValue('next_kgb_date', $this->formatTanggalIndo($request->query('next_kgb_date')));
        $template->setValue('ttd_pengirim', '${ttd_pengirim}');
        $template->setValue('nama_kasek', $request->query('nama_kasek', '-'));

        $pegawaiName = str_replace([' ', '/'], '-', strtolower($pegawai->nama));
        $filename = 'kgb-pns-'.$pegawaiName.'.docx';
        $tempFile = tempnam(sys_get_temp_dir(), 'kgb_pns_').'.docx';
        $template->saveAs($tempFile);

        return response()->download($tempFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ])->deleteFileAfterSend(true);
    }

    public function generatePppkPdf(Request $request)
    {
        $pegawai = Pegawai::findOrFail($request->query('pegawai_id'));

        $templatePath = storage_path('app/templates/kgb-pppk.docx');
        $template = new TemplateProcessor($templatePath);

        $template->setValue('nomor_naskah', $request->query('nomor_naskah', '-'));
        $template->setValue('tanggal_naskah', $this->formatTanggalIndo($request->query('tanggal_naskah')));
        $template->setValue('ibu_kota_provinsi', $request->query('ibu_kota_provinsi', 'Makassar'));
        $template->setValue('provinsi_upper', 'SULAWESI SELATAN');
        $template->setValue('provinsi_title', 'Sulawesi Selatan');
        $template->setValue('nama', $pegawai->nama);
        $template->setValue('ni_pppk', $request->query('ni_pppk', $pegawai->nip_baru ?? '-'));
        $template->setValue('jabatan_golongan', $request->query('jabatan_golongan', '-'));
        $template->setValue('masa_perjanjian_kerja', $request->query('masa_perjanjian_kerja', '-'));
        $template->setValue('perpanjangan_perjanjian_kerja', $request->query('perpanjangan_perjanjian_kerja', '-'));
        $template->setValue('unit_kerja', $request->query('unit_kerja', $pegawai->unit_kerja ?? 'Bawaslu Provinsi Sulawesi Selatan'));
        $template->setValue('gaji_lama', $request->query('gaji_lama', '-'));
        $template->setValue('sk_pejabat', $request->query('sk_pejabat', '-'));
        $template->setValue('sk_tanggal', $this->formatTanggalIndo($request->query('sk_tanggal')));
        $template->setValue('sk_nomor', $request->query('sk_nomor', '-'));
        $template->setValue('sk_tmt', $this->formatTanggalIndo($request->query('sk_tmt')));
        $template->setValue('sk_mkg', $request->query('sk_mkg', '-'));
        $template->setValue('gaji_baru', $request->query('gaji_baru', '-'));
        $template->setValue('masa_kerja_baru', $request->query('masa_kerja_baru', '-'));
        $template->setValue('tmt_baru', $this->formatTanggalIndo($request->query('tmt_baru')));
        $template->setValue('ttd_pengirim', $request->query('ttd_pengirim', ''));
        $template->setValue('nama_kasek', $request->query('nama_kasek', '-'));

        $pegawaiName = str_replace([' ', '/'], '-', strtolower($pegawai->nama));
        $filename = 'kgb-pppk-'.$pegawaiName.'.docx';
        $tempFile = tempnam(sys_get_temp_dir(), 'kgb_pppk_').'.docx';
        $template->saveAs($tempFile);

        return response()->download($tempFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ])->deleteFileAfterSend(true);
    }
}
