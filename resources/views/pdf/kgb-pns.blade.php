<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kenaikan Gaji Berkala PNS</title>
    <style>
        @page {
            margin-top: 1.8cm;
            margin-bottom: 1.8cm;
            margin-left: 2cm;
            margin-right: 2cm;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #000;
        }
        .letterhead {
            margin: -10px 0 14px -4px;
        }
        .letterhead img {
            width: 220px;
            height: auto;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .meta-table td {
            vertical-align: top;
        }
        .meta-left {
            width: 60%;
        }
        .meta-right {
            width: 40%;
            text-align: right;
        }
        .content-section {
            margin-bottom: 15px;
            text-align: justify;
        }
        .data-table {
            width: 100%;
            margin-bottom: 15px;
            margin-left: 10px;
        }
        .data-table td {
            padding: 2px 5px;
            vertical-align: top;
        }
        .data-label {
            width: 35%;
        }
        .data-colon {
            width: 3%;
            text-align: center;
        }
        .data-value {
            width: 62%;
        }
        .signature-container {
            width: 100%;
            margin-top: 30px;
            page-break-inside: avoid;
        }
        .signature-table {
            width: 100%;
        }
        .signature-space {
            height: 60px;
        }
        .tembusan-section {
            margin-top: 40px;
            font-size: 9.5pt;
            line-height: 1.3;
            page-break-inside: avoid;
        }
        .tembusan-title {
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 5px;
        }
        .tembusan-list {
            margin: 0;
            padding-left: 20px;
        }
    </style>
</head>
<body>

    {{-- Template Word memakai kop sebagai gambar pada header. --}}
    @if(file_exists(public_path('images/bawaslu-sulsel-logo.png')))
        <div class="letterhead">
            <img src="{{ public_path('images/bawaslu-sulsel-logo.png') }}" alt="Bawaslu Provinsi Sulawesi Selatan">
        </div>
    @endif

    <!-- Metadata Surat -->
    <table class="meta-table">
        <tr>
            <td class="meta-left">
                <table>
                    <tr>
                        <td style="width: 80px; padding-bottom: 2px;">Nomor</td>
                        <td style="width: 10px;">:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td style="padding-bottom: 2px;">Sifat</td>
                        <td>:</td>
                        <td>Segera</td>
                    </tr>
                    <tr>
                        <td style="padding-bottom: 2px;">Lampiran</td>
                        <td>:</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Perihal</td>
                        <td>:</td>
                        <td style="font-weight: bold;">Kenaikan Gaji Berkala</td>
                    </tr>
                </table>
            </td>
            <td class="meta-right">
                {{ $ibu_kota_provinsi }}, {{ $tanggal_naskah ? \Carbon\Carbon::parse($tanggal_naskah)->translatedFormat('d F Y') : '' }}
            </td>
        </tr>
    </table>

    <!-- Penerima -->
    <div class="content-section" style="margin-bottom: 20px;">
        Yth:<br>
        <span style="font-weight: bold;">KEPALA KANTOR PELAYANAN<br>PERBENDAHARAAN NEGARA</span><br>
        di<br>
        <span style="margin-left: 20px;">Tempat</span>
    </div>

    <!-- Pembuka -->
    <div class="content-section">
        Dengan ini diberitahukan, bahwa sehubungan telah dipenuhinya masa kerja dan syarat-syarat lainnya kepada :
    </div>

    <!-- Data Pegawai -->
    <table class="data-table">
        <tr>
            <td class="data-label">Nama</td>
            <td class="data-colon">:</td>
            <td class="data-value" style="font-weight: bold;">{{ $pegawai->nama }}</td>
        </tr>
        <tr>
            <td class="data-label">NIP</td>
            <td class="data-colon">:</td>
            <td class="data-value">{{ $pegawai->nip_baru }}</td>
        </tr>
        <tr>
            <td class="data-label">Pangkat</td>
            <td class="data-colon">:</td>
            <td class="data-value">{{ $pegawai->pangkat ?? '-' }} / {{ $pegawai->gol_nama ?? '-' }}</td>
        </tr>
        <tr>
            <td class="data-label">Kantor/Tempat</td>
            <td class="data-colon">:</td>
            <td class="data-value">{{ $pegawai->unit_kerja ?? '-' }}</td>
        </tr>
        <tr>
            <td class="data-label">Gaji Pokok lama</td>
            <td class="data-colon">:</td>
            <td class="data-value">{{ $gaji_pokok_lama }}</td>
        </tr>
    </table>

    <!-- Atas Dasar SK -->
    <div class="content-section">
        Atas dasar Surat Keputusan terakhir Gaji/Pangkat yang ditetapkan :
    </div>
    <table class="data-table">
        <tr>
            <td class="data-label">Oleh Pejabat</td>
            <td class="data-colon">:</td>
            <td class="data-value">{{ $sk_pejabat }}</td>
        </tr>
        <tr>
            <td class="data-label">Tanggal</td>
            <td class="data-colon">:</td>
            <td class="data-value">{{ $sk_tanggal ? \Carbon\Carbon::parse($sk_tanggal)->translatedFormat('d F Y') : '' }}</td>
        </tr>
        <tr>
            <td class="data-label">Nomor</td>
            <td class="data-colon">:</td>
            <td class="data-value">{{ $sk_nomor }}</td>
        </tr>
        <tr>
            <td class="data-label">Tanggal Mulai Berlakunya Gaji tersebut</td>
            <td class="data-colon">:</td>
            <td class="data-value">{{ $sk_tmt ? \Carbon\Carbon::parse($sk_tmt)->translatedFormat('d F Y') : '' }}</td>
        </tr>
        <tr>
            <td class="data-label">Masa kerja golongan pada Tanggal tersebut</td>
            <td class="data-colon">:</td>
            <td class="data-value">{{ $sk_mkg }}</td>
        </tr>
    </table>

    <!-- Diberikan Kenaikan -->
    <div class="content-section">
        Diberikan Kenaikan Gaji Berkala Hingga memperoleh :
    </div>
    <table class="data-table">
        <tr>
            <td class="data-label">Gaji Pokok Baru</td>
            <td class="data-colon">:</td>
            <td class="data-value" style="font-weight: bold;">{{ $gaji_pokok_baru }}</td>
        </tr>
        <tr>
            <td class="data-label">Berdasarkan Masa Kerja</td>
            <td class="data-colon">:</td>
            <td class="data-value">{{ $masa_kerja_baru }}</td>
        </tr>
        <tr>
            <td class="data-label">Dalam Golongan/Ruang</td>
            <td class="data-colon">:</td>
            <td class="data-value">{{ $golongan_ruang_baru }}</td>
        </tr>
        <tr>
            <td class="data-label">Mulai Tanggal</td>
            <td class="data-colon">:</td>
            <td class="data-value" style="font-weight: bold;">{{ $tmt_baru ? \Carbon\Carbon::parse($tmt_baru)->translatedFormat('d F Y') : '' }}</td>
        </tr>
        <tr>
            <td class="data-label">Kenaikan Gaji Berkala Berikutnya</td>
            <td class="data-colon">:</td>
            <td class="data-value">{{ $next_kgb_date ? \Carbon\Carbon::parse($next_kgb_date)->translatedFormat('d F Y') : '' }}</td>
        </tr>
    </table>

    <!-- Penutup -->
    <div class="content-section">
        Diharapkan agar sesuai dengan Peraturan Pemerintah Nomor 5 Tahun 2024 kepada Pegawai tersebut dapat dibayarkan penghasilannya berdasarkan gaji pokoknya yang baru.
    </div>

    <!-- Tanda Tangan -->
    <div class="signature-container">
        <table class="signature-table">
            <tr>
                <td style="width: 50%;"></td>
                <td style="width: 50%; text-align: left; padding-left: 20px;">
                    Kepala Sekretariat Bawaslu Provinsi,<br><br>
                    @if($ttd_pengirim)
                        <span style="font-style: italic; font-size: 10pt; color: #555;">{{ $ttd_pengirim }}</span><br>
                    @endif
                    <div class="signature-space"></div>
                    <span style="font-weight: bold; text-decoration: underline;">{{ $nama_kasek }}</span>
                </td>
            </tr>
        </table>
    </div>

    <!-- Tembusan -->
    <div class="tembusan-section">
        <div class="tembusan-title">Tembusan:</div>
        <ol class="tembusan-list">
            <li>Sekretaris Jenderal Badan Pengawas Pemilihan Umum;</li>
            <li>Ketua Badan Pengawas Pemilihan Umum Provinsi Sulawesi Selatan;</li>
            <li>Anggota Badan Pengawas Pemilihan Umum Provinsi Sulawesi Selatan;</li>
            <li>Pegawai yang bersangkutan; dan</li>
            <li>Pertinggal.</li>
        </ol>
    </div>

</body>
</html>
