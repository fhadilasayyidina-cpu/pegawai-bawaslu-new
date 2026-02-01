<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cuti;
use Barryvdh\DomPDF\Facade\Pdf;

class CutiController extends Controller
{
    public function generatePdf(int $id)
    {
        $cuti = Cuti::with('pegawai')->findOrFail($id);

        $pdf = Pdf::loadView('pdf.surat-cuti', [
            'cuti' => $cuti,
        ]);

        $pegawaiName = str_replace([' ', '/'], '-', strtolower($cuti->pegawai->nama));
        $filename = 'surat-cuti-'.$pegawaiName.'.pdf';

        return $pdf->stream($filename);
    }
}
