<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int $pegawai_id
 * @property \Illuminate\Support\Carbon $tanggal
 * @property string $status
 * @property string|null $keterangan
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $createdBy
 * @property-read \App\Models\Pegawai $pegawai
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi wherePegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Absensi whereUpdatedAt($value)
 */
	class Absensi extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $pegawai_id
 * @property string $nomor_surat
 * @property string $jenis_cuti
 * @property string $alasan
 * @property \Illuminate\Support\Carbon $tanggal_mulai
 * @property \Illuminate\Support\Carbon $tanggal_selesai
 * @property int $lama_hari
 * @property string|null $keterangan
 * @property string $nama_kepala_sekretariat
 * @property string|null $nip_kepala_sekretariat
 * @property string $nama_sekjen
 * @property string|null $nip_sekjen
 * @property string|null $nomor_surat_edaran
 * @property string|null $status_dokter
 * @property string|null $nama_dokter
 * @property string|null $nomor_surat_dokter
 * @property string|null $jenis_melahirkan
 * @property \Illuminate\Support\Carbon|null $tanggal_perkiraan_lahir
 * @property bool $tanpa_gaji
 * @property string|null $alasan_luar_tanggungan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Pegawai $pegawai
 * @method static \Database\Factories\CutiFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuti newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuti newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuti query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuti whereAlasan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuti whereAlasanLuarTanggungan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuti whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuti whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuti whereJenisCuti($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuti whereJenisMelahirkan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuti whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuti whereLamaHari($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuti whereNamaDokter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuti whereNamaKepalaSekretariat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuti whereNamaSekjen($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuti whereNipKepalaSekretariat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuti whereNipSekjen($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuti whereNomorSurat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuti whereNomorSuratDokter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuti whereNomorSuratEdaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuti wherePegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuti whereStatusDokter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuti whereTanggalMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuti whereTanggalPerkiraanLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuti whereTanggalSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuti whereTanpaGaji($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Cuti whereUpdatedAt($value)
 */
	class Cuti extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property \Illuminate\Support\Carbon $date
 * @property string $description
 * @property bool $is_imported
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\HariLiburFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HariLibur newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HariLibur newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HariLibur query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HariLibur whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HariLibur whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HariLibur whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HariLibur whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HariLibur whereIsImported($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HariLibur whereUpdatedAt($value)
 */
	class HariLibur extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $pegawai_id
 * @property int|null $created_by
 * @property string $jenis_kgb
 * @property string $nomor_naskah
 * @property \Illuminate\Support\Carbon $tanggal_naskah
 * @property \Illuminate\Support\Carbon $tmt_baru
 * @property \Illuminate\Support\Carbon|null $next_kgb_date
 * @property array<array-key, mixed> $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $creator
 * @property-read \App\Models\Pegawai $pegawai
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KgbRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KgbRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KgbRecord query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KgbRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KgbRecord whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KgbRecord whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KgbRecord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KgbRecord whereJenisKgb($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KgbRecord whereNextKgbDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KgbRecord whereNomorNaskah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KgbRecord wherePegawaiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KgbRecord whereTanggalNaskah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KgbRecord whereTmtBaru($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|KgbRecord whereUpdatedAt($value)
 */
	class KgbRecord extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string|null $nip_baru
 * @property string|null $nip_lama
 * @property string|null $id_absensi
 * @property string|null $nama
 * @property string|null $gelar_depan
 * @property string|null $gelar_blk
 * @property string|null $tempat_lahir_nama
 * @property string|null $jenis_kelamin
 * @property string|null $gol_darah
 * @property string|null $agama_nama
 * @property string|null $jenis_kawin_nama
 * @property string|null $nik
 * @property string|null $nomor_hp
 * @property string|null $email
 * @property string|null $email_gov
 * @property string|null $alamat
 * @property string|null $foto
 * @property \Illuminate\Support\Carbon|null $tgl_lahir
 * @property int|null $usia
 * @property string|null $npwp_nomor
 * @property string|null $bpjs
 * @property string|null $kartu_pegawai
 * @property string|null $nomor_sk_cpns
 * @property \Illuminate\Support\Carbon|null $tgl_sk_cpns
 * @property \Illuminate\Support\Carbon|null $tmt_cpns
 * @property string|null $nomor_sk_pns
 * @property \Illuminate\Support\Carbon|null $tgl_sk_pns
 * @property \Illuminate\Support\Carbon|null $tmt_pns
 * @property string|null $no_sk_dpk_penugasan_kontrak
 * @property \Illuminate\Support\Carbon|null $tgl_sk_dpk_penugasan_kontrak
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $tgl_kgb_terakhir
 * @property string|null $keterangan_status
 * @property string|null $gol_awal_nama
 * @property string|null $gol_nama
 * @property \Illuminate\Support\Carbon|null $tmt_golongan
 * @property int|null $mkgol
 * @property string|null $jenis_jabatan_nama
 * @property string|null $jabatan_nama
 * @property \Illuminate\Support\Carbon|null $tmt_jabatan
 * @property string|null $jabatan_non_definitif
 * @property string|null $jabatan_non_definitif_1
 * @property int|null $mkjab
 * @property int|null $jumlah
 * @property string|null $kelas
 * @property string|null $kelas_jabatan
 * @property string|null $kelompok_jabatan
 * @property string|null $nm_kelompok_jabatan
 * @property string|null $nama_kelompok_jabatan
 * @property string|null $pangkat
 * @property string|null $proyeksi_jf
 * @property string|null $tingkat_pendidikan_nama
 * @property string|null $pendidikan_nama
 * @property string|null $tahun_lulus
 * @property string|null $riwayat_diklatpim
 * @property string|null $satuan_kerja
 * @property string|null $unit_kerja
 * @property string|null $unit_organisasi
 * @property string|null $unor_nama
 * @property string|null $instansi_induk_nama
 * @property string|null $eselon
 * @property string|null $divisi
 * @property string|null $ukm
 * @property string|null $range_umur
 * @property string|null $provinsi
 * @property string|null $kab_kota
 * @property string|null $jenis_pegawai
 * @property string|null $status_kepegwaian
 * @property int $sisa_cuti_tahun_berjalan
 * @property int $sisa_cuti_tahun_lalu
 * @property int $sisa_cuti_dua_tahun_lalu
 * @property int $jumlah_cuti_besar_diambil
 * @property \Illuminate\Support\Carbon|null $tanggal_cuti_besar_terakhir
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $sk_cpns_drive_link
 * @property string|null $sk_pns_drive_link
 * @property string|null $sk_kgb_drive_link
 * @property string|null $karpeg_drive_link
 * @property string|null $npwp_drive_link
 * @property string|null $bpjs_drive_link
 * @property string|null $sk_golongan_awal_drive_link
 * @property string|null $sk_golongan_terakhir_drive_link
 * @property string|null $sk_jabatan_drive_link
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Absensi> $absensis
 * @property-read int|null $absensis_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Cuti> $cutis
 * @property-read int|null $cutis_count
 * @property-read string $foto_url
 * @property-read string $initials
 * @method static \Database\Factories\PegawaiFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereAgamaNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereBpjs($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereBpjsDriveLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereDivisi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereEmailGov($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereEselon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereGelarBlk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereGelarDepan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereGolAwalNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereGolDarah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereGolNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereIdAbsensi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereInstansiIndukNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereJabatanNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereJabatanNonDefinitif($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereJabatanNonDefinitif1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereJenisJabatanNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereJenisKawinNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereJenisKelamin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereJenisPegawai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereJumlah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereJumlahCutiBesarDiambil($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereKabKota($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereKarpegDriveLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereKartuPegawai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereKelas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereKelasJabatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereKelompokJabatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereKeteranganStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereMkgol($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereMkjab($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereNamaKelompokJabatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereNipBaru($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereNipLama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereNmKelompokJabatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereNoSkDpkPenugasanKontrak($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereNomorHp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereNomorSkCpns($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereNomorSkPns($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereNpwpDriveLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereNpwpNomor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai wherePangkat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai wherePendidikanNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereProvinsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereProyeksiJf($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereRangeUmur($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereRiwayatDiklatpim($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereSatuanKerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereSisaCutiDuaTahunLalu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereSisaCutiTahunBerjalan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereSisaCutiTahunLalu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereSkCpnsDriveLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereSkGolonganAwalDriveLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereSkGolonganTerakhirDriveLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereSkJabatanDriveLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereSkKgbDriveLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereSkPnsDriveLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereStatusKepegwaian($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereTahunLulus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereTanggalCutiBesarTerakhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereTempatLahirNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereTglKgbTerakhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereTglLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereTglSkCpns($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereTglSkDpkPenugasanKontrak($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereTglSkPns($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereTingkatPendidikanNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereTmtCpns($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereTmtGolongan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereTmtJabatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereTmtPns($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereUkm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereUnitKerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereUnitOrganisasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereUnorNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereUsia($value)
 */
	class Pegawai extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nama
 * @property \App\Enums\PimpinanJabatan $jabatan
 * @property string $kab_kota
 * @property string|null $email
 * @property string|null $no_hp
 * @property string|null $foto
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $foto_url
 * @method static \Database\Factories\PimpinanFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pimpinan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pimpinan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pimpinan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pimpinan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pimpinan whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pimpinan whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pimpinan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pimpinan whereJabatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pimpinan whereKabKota($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pimpinan whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pimpinan whereNoHp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pimpinan whereUpdatedAt($value)
 */
	class Pimpinan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $jenis_pegawai
 * @property string $golongan
 * @property int $mkg_tahun
 * @property int $gaji_pokok
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalaryMatrix newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalaryMatrix newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalaryMatrix query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalaryMatrix whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalaryMatrix whereGajiPokok($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalaryMatrix whereGolongan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalaryMatrix whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalaryMatrix whereJenisPegawai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalaryMatrix whereMkgTahun($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SalaryMatrix whereUpdatedAt($value)
 */
	class SalaryMatrix extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $two_factor_confirmed_at
 * @property Role $role
 * @property string|null $access_scope
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereAccessScope($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorConfirmedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class User extends \Eloquent {}
}

