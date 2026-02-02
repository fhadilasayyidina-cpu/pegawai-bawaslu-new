<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page {
            margin-top: 2cm;
            margin-bottom: 2cm;
            margin-left: 2.5cm;
            margin-right: 2.5cm;
        }
        body {
            font-family: Times New Roman, serif;
            font-size: 12pt;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
        }
        .logo {
            width: 70px;
            height: 70px;
            margin-bottom: 5px;
        }
        .header-title {
            font-weight: bold;
            font-size: 14pt;
            margin-bottom: 2px;
        }
        .header-subtitle {
            font-size: 11pt;
            margin-bottom: 5px;
        }
        .surat-header {
            text-align: center;
            margin: 15px 0 10px 0;
        }
        .surat-title {
            font-weight: bold;
            font-size: 12pt;
            text-decoration: underline;
            margin-bottom: 15px;
        }
        .info-table {
            width: 100%;
            margin: 10px 0;
        }
        .info-table td {
            padding: 4px 8px;
            vertical-align: top;
        }
        .info-table td:first-child {
            width: 180px;
        }
        .signature {
            text-align: center;
            margin-top: 30px;
            margin-left: auto;
            margin-right: 0;
            width: 45%;
            float: right;
        }
        .signature-space {
            height: 80px;
        }
        .footer {
            clear: both;
            margin-top: 40px;
            font-size: 11pt;
            page-break-inside: avoid;
        }
        .footer p {
            margin: 2px 0;
        }
    </style>
</head>
<body>
    <!-- Header BAWASLU -->
    <div class="header">
        <div class="header-title">BADAN PENGAWAS PEMILIHAN UMUM</div>
        <div class="header-title">REPUBLIK INDONESIA</div>
    </div>

    <!-- Judul Surat -->
    <div class="surat-header">
        <div class="surat-title">SURAT CUTI</div>
    </div>

    <!-- Table Informasi Cuti -->
    <table class="info-table">
        <tr>
            <td>Nomor Surat</td>
            <td>: {{ $cuti->nomor_surat }}</td>
        </tr>
        <tr>
            <td>Nama</td>
            <td>: {{ $cuti->pegawai->nama }}</td>
        </tr>
        <tr>
            <td>NIP</td>
            <td>: {{ $cuti->pegawai->nip_baru }}</td>
        </tr>
        <tr>
            <td>Pangkat/Golongan</td>
            <td>: {{ $cuti->pegawai->pangkat ?? '-' }} ({{ $cuti->pegawai->gol_nama ?? '-' }})</td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>: {{ $cuti->pegawai->jabatan_nama ?? '-' }}</td>
        </tr>
        <tr>
            <td>Jenis Cuti</td>
            <td>: {{ ucfirst($cuti->jenis_cuti) }}</td>
        </tr>
        <tr>
            <td>Alasan</td>
            <td>: {{ $cuti->alasan }}</td>
        </tr>
        <tr>
            <td>Lama Hari</td>
            <td>: {{ $cuti->lama_hari }} ({{ terbilang($cuti->lama_hari) }}) Hari</td>
        </tr>
        <tr>
            <td>Tanggal Mulai</td>
            <td>: {{ $cuti->tanggal_mulai->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td>Tanggal Selesai</td>
            <td>: {{ $cuti->tanggal_selesai->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td>Alamat Selama Cuti</td>
            <td>: {{ $cuti->keterangan ?? '-' }}</td>
        </tr>
    </table>

    <!-- Tanda Tangan -->
    <div class="signature">
        <p>Jakarta, {{ now()->translatedFormat('d F Y') }}</p>
        <p>Kepala Sekretariat</p>
        <div class="signature-space"></div>
        <p><strong>{{ $cuti->nama_kepala_sekretariat }}</strong></p>
        <p>NIP. {{ $cuti->nip_kepala_sekretariat }}</p>
    </div>

    <!-- Tembusan -->
    <div class="footer">
        <p>Tembusan:</p>
        <p>1. Sekretaris Jenderal BAWASLU RI;</p>
        <p>2. Kepala Sekretariat BAWASLU RI;</p>
        <p>3. Yang bersangkutan;</p>
        <p>4. Arsip.</p>
    </div>
</body>
</html>
