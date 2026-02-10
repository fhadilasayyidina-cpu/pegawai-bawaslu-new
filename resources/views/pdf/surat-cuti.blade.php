<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page {
            margin-top: 0.7cm;
            margin-bottom: 0.7cm;
            margin-left: 0.7cm;
            margin-right: 0.7cm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 10.5pt;
            line-height: 1.1;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 5px;
        }
        .header-title {
            font-weight: bold;
            font-size: 12pt;
            margin-bottom: 2px;
        }
        .header-box {
            margin-top: 5px;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, td {
            border: 1px solid #000;
        }
        td {
            padding: 3px 5px;
        }
        .section-title {
            background-color: #d0d0d0;
            font-weight: bold;
            padding: 4px 5px !important;
        }
        .center {
            text-align: center;
        }
        .right {
            text-align: right;
        }
        .keterangan-text {
            width: 100%;
        }
        .keterangan-text td {
            border: none;
            padding: 1px 5px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-title">FORMULIR PERMINTAAN DAN PEMBERIAN CUTI</div>
    </div>

    <!-- Header Alamat -->
    <div class="header-box">
        Makassar, {{ $cuti->tanggal_mulai->subDays(14)->format('d F Y') }}<br>
        Kepada:<br>
        Yth. Sekretaris Jenderal Bawaslu<br>
        di-<br>
        Jakarta
    </div>

    <!-- Bagian I - Data Pegawai -->
    <table>
        <tr><td colspan="4" class="section-title">I. DATA PEGAWAI</td></tr>
        <tr>
            <td width="15%">Nama</td>
            <td width="35%">{{ $cuti->pegawai->nama }}</td>
            <td width="15%">NIP</td>
            <td width="35%">{{ $cuti->pegawai->nip_baru }}</td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>{{ $cuti->pegawai->jabatan_nama ?? '-' }}</td>
            <td>Masa Kerja</td>
            <td>
                @if($cuti->pegawai->tmt_cpns)
                    {{ floor(\Carbon\Carbon::parse($cuti->pegawai->tmt_cpns)->diffInMonths(now()) / 12) }} Tahun
                    {{ \Carbon\Carbon::parse($cuti->pegawai->tmt_cpns)->diffInMonths(now()) % 12 }} Bulan
                @else
                    -
                @endif
            </td>
        </tr>
        <tr>
            <td>Unit Kerja</td>
            <td colspan="3">{{ $cuti->pegawai->unit_kerja ?? '-' }}</td>
        </tr>
    </table>

    <!-- Bagian II - Jenis Cuti -->
    <table style="margin-top: 3px;">
        <tr><td colspan="4" class="section-title">II. JENIS CUTI YANG DIAMBIL **</td></tr>
        <tr>
            <td width="42%">1. Cuti Tahunan</td>
            <td width="8%" class="center">{{ $cuti->jenis_cuti === 'tahunan' ? '√' : '-' }}</td>
            <td width="42%">2. Cuti Besar</td>
            <td width="8%" class="center">{{ $cuti->jenis_cuti === 'besar' ? '√' : '-' }}</td>
        </tr>
        <tr>
            <td>3. Cuti Sakit</td>
            <td class="center">{{ $cuti->jenis_cuti === 'sakit' ? '√' : '-' }}</td>
            <td>4. Cuti Melahirkan</td>
            <td class="center">{{ $cuti->jenis_cuti === 'melahirkan' ? '√' : '-' }}</td>
        </tr>
        <tr>
            <td>5. Cuti Karena Alasan Penting</td>
            <td class="center">{{ $cuti->jenis_cuti === 'alasan_penting' ? '√' : '-' }}</td>
            <td>6. Cuti di Luar Tanggungan Negara</td>
            <td class="center">{{ $cuti->jenis_cuti === 'luar_tanggungan' ? '√' : '-' }}</td>
        </tr>
    </table>

    <!-- Bagian III - Alasan Cuti -->
    <table style="margin-top: 3px;">
        <tr><td class="section-title">III. ALASAN CUTI</td></tr>
        <tr><td>{{ $cuti->alasan }}</td></tr>
    </table>

    <!-- Bagian IV - Lamanya Cuti -->
    <table style="margin-top: 3px;">
        <tr><td colspan="6" class="section-title">IV. LAMANYA CUTI</td></tr>
        <tr>
            <td style="border-right:none" width="10%">Selama</td>
            <td style="border-left:none; border-right:none" width="30%">
                {{ $cuti->lama_hari }} ({{ terbilang($cuti->lama_hari) }}) hari kerja
            </td>
            <td style="border-left:none; border-right:none" width="15%">Mulai tanggal</td>
            <td style="border-left:none; border-right:none" width="20%">{{ $cuti->tanggal_mulai->translatedFormat('d F Y') }}</td>
            <td style="border-left:none; border-right:none" width="5%">s.d</td>
            <td style="border-left:none" width="20%">{{ $cuti->tanggal_selesai->translatedFormat('d F Y') }}</td>
        </tr>
    </table>

    <!-- Bagian V - Catatan Cuti -->
    <table style="margin-top: 3px;">
        <tr><td colspan="5" class="section-title">V. CATATAN CUTI ***</td></tr>
        <tr>
            <td colspan="2" width="45%">1. Cuti Tahunan</td>
            <td width="10%" class="center">Sisa</td>
            <td width="10%" class="center">Keterangan</td>
            <td width="35%">2. Cuti Besar</td>
        </tr>
        <tr>
            <td width="10%" class="center">Tahun</td>
            <td width="35%" class="center">Sisa</td>
            <td rowspan="4" class="center"></td>
            <td rowspan="4" class="center"></td>
            <td>3. Cuti Sakit</td>
        </tr>
        <tr>
            <td>N-2</td>
            <td class="center">{{ $cuti->pegawai->sisa_cuti_dua_tahun_lalu ?? 0 }}</td>
            <td>4. Cuti Melahirkan</td>
        </tr>
        <tr>
            <td>N-1</td>
            <td class="center">{{ $cuti->pegawai->sisa_cuti_tahun_lalu ?? 0 }}</td>
            <td>5. Cuti Alasan Penting</td>
        </tr>
        <tr>
            <td>N</td>
            <td class="center">{{ $cuti->pegawai->sisa_cuti_tahun_berjalan ?? 12 }}</td>
            <td>6. Cuti di Luar Tanggungan Negara</td>
        </tr>
    </table>

    <!-- Bagian VI - Alamat Selama Cuti + Tanda Tangan Pegawai -->
    <table style="margin-top: 3px;">
        <tr><td colspan="3" class="section-title">VI. ALAMAT SELAMA MENJALANKAN CUTI</td></tr>
        <tr>
            <td rowspan="2" width="70%">{{ $cuti->keterangan ?? '-' }}</td>
            <td width="15%" style="border-right:none">Telepon</td>
            <td width="15%" style="border-left:none">{{ $cuti->pegawai->nomor_hp ?? '-' }}</td>
        </tr>
        <tr>
            <td colspan="2" class="right" style="padding-top:20px; border:none">
                Hormat saya,<br><br><br><br>
                <strong>( {{ $cuti->pegawai->nama }} )</strong><br>
                NIP. {{ $cuti->pegawai->nip_baru }}
            </td>
        </tr>
    </table>

    <!-- Bagian VII - Pertimbangan Atasan Langsung -->
    <table style="margin-top: 3px;">
        <tr><td colspan="4" class="section-title">VII. PERTIMBANGAN ATASAN LANGSUNG **</td></tr>
        <tr class="center">
            <td width="22%">Disetujui</td>
            <td width="26%">Perubahan ****</td>
            <td width="26%">Ditangguhkan ****</td>
            <td width="26%">Tidak Disetujui ****</td>
        </tr>
        <tr>
            <td class="center">√</td><td></td><td></td><td></td>
        </tr>
        <tr>
            <td colspan="4" class="right" style="padding: 20px 10px 10px 0;">
                Kepala Sekretariat,<br>
                Bawaslu Provinsi Sulawesi Selatan<br><br><br><br>
                <strong>( Awaluddin Mustafa, S.E., M.Si )</strong><br>
                NIP. 19740712 200212 1 006
            </td>
        </tr>
    </table>

    <!-- Bagian VIII - Keputusan Pejabat -->
    <table style="margin-top: 3px;">
        <tr><td colspan="4" class="section-title">VIII. KEPUTUSAN PEJABAT YANG BERWENANG MEMBERIKAN CUTI **</td></tr>
        <tr class="center">
            <td width="22%">Disetujui</td>
            <td width="26%">Perubahan ****</td>
            <td width="26%">Ditangguhkan ****</td>
            <td width="26%">Tidak Disetujui ****</td>
        </tr>
        <tr>
            <td class="center">√</td><td></td><td></td><td></td>
        </tr>
        <tr>
            <td colspan="2" style="border-bottom:none; border-right:none">
                <table class="keterangan-text">
                    <tr><td colspan="3" style="padding: 5px 5px 2px 5px;"><strong>Keterangan:</strong></td></tr>
                    <tr><td style="width: 15px;">*</td><td style="width: 10px;">=</td><td>Coret yang tidak perlu</td></tr>
                    <tr><td>**</td><td>=</td><td>Pilih salah satu dengan tanda centang (√)</td></tr>
                    <tr><td>***</td><td>=</td><td>Diisi oleh pejabat kepegawaian</td></tr>
                    <tr><td>****</td><td>=</td><td>Beri tanda centang dan alasannya</td></tr>
                    <tr><td>N</td><td>=</td><td>Cuti tahun berjalan</td></tr>
                    <tr><td>N-1</td><td>=</td><td>Sisa cuti 1 tahun sebelumnya</td></tr>
                    <tr><td>N-2</td><td>=</td><td>Sisa cuti 2 tahun sebelumnya</td></tr>
                </table>
            </td>
            <td colspan="2" class="right" style="padding: 20px 10px 10px 0; border-top:none; border-left:none">
                Sekretaris Jenderal,<br><br><br><br><br>
                <strong>( Ferdinand Eskol Tiar Sirait )</strong><br>
                NIP. 19741201 199303 1 001
            </td>
        </tr>
    </table>
</body>
</html>
