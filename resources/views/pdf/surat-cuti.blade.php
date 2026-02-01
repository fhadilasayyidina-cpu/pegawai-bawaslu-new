<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Times New Roman, serif;
            font-size: 12pt;
            line-height: 1.5;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            width: 80px;
            height: 80px;
            margin-bottom: 10px;
        }
        .title {
            font-weight: bold;
            font-size: 14pt;
            margin-bottom: 5px;
        }
        .subtitle {
            font-size: 11pt;
            margin-bottom: 20px;
        }
        .surat-header {
            text-align: center;
            margin: 20px 0;
        }
        .surat-title {
            font-weight: bold;
            font-size: 12pt;
            text-decoration: underline;
            margin-bottom: 20px;
        }
        .content {
            margin-bottom: 20px;
        }
        .content p {
            margin: 5px 0;
        }
        .table {
            width: 100%;
            margin: 20px 0;
        }
        .table td {
            padding: 5px;
        }
        .signature {
            float: right;
            width: 40%;
            text-align: center;
            margin-top: 50px;
        }
        .signature-space {
            height: 100px;
        }
        .footer {
            clear: both;
            margin-top: 30px;
            font-size: 10pt;
        }
    </style>
</head>
<body>
    <!-- Header BAWASLU -->
    <div class="header">
        <div class="title">BADAN PENGAWAS PEMILIHAN UMUM</div>
        <div class="title">REPUBLIK INDONESIA</div>
        <div class="subtitle">Jalan Ir. H. Juanda No. 11, Jakarta 10110</div>
    </div>

    <!-- Judul Surat -->
    <div class="surat-header">
        <div class="surat-title">SURAT CUTI</div>
        <div>Nomor: {{ $cuti->nomor_surat }}</div>
    </div>

    <!-- Isi Surat -->
    <div class="content">
        <p>Yang bertanda tangan di bawah ini:</p>

        <table class="table">
            <tr>
                <td width="150">Nama</td>
                <td>: {{ $cuti->pegawai->nama }}</td>
            </tr>
            <tr>
                <td>NIP</td>
                <td>: {{ $cuti->pegawai->nip_baru }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>: {{ $cuti->pegawai->jabatan_nama }}</td>
            </tr>
        </table>

        <p>Dengan ini memberikan cuti kepada:</p>

        <table class="table">
            <tr>
                <td width="150">Nama</td>
                <td>: {{ $cuti->pegawai->nama }}</td>
            </tr>
            <tr>
                <td>NIP</td>
                <td>: {{ $cuti->pegawai->nip_baru }}</td>
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
                <td>Lama Cuti</td>
                <td>: {{ $cuti->lama_hari }} hari</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>: {{ $cuti->tanggal_mulai->translatedFormat('d F Y') }} s.d. {{ $cuti->tanggal_selesai->translatedFormat('d F Y') }}</td>
            </tr>
        </table>

        <p>Demikian surat cuti ini dibuat untuk dipergunakan sebagaimana mestinya.</p>
    </div>

    <!-- Tanda Tangan -->
    <div class="signature">
        <p>Jakarta, {{ now()->translatedFormat('d F Y') }}</p>
        <p>Kepala Sekretariat</p>
        <div class="signature-space"></div>
        <p><strong>{{ $cuti->nama_kepala_sekretariat }}</strong></p>
        @if($cuti->nip_kepala_sekretariat)
        <p>NIP. {{ $cuti->nip_kepala_sekretariat }}</p>
        @endif
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Tembusan:</p>
        <p>1. Sekretaris Jenderal BAWASLU RI</p>
        <p>2. Arsip</p>
    </div>
</body>
</html>
