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
 * @property string|null $nip_baru
 * @property string|null $nip_lama
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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereAgamaNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereBpjs($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereDivisi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereEmailGov($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereEselon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereGelarBlk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereGelarDepan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereGolAwalNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereGolDarah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereGolNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereInstansiIndukNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereJabatanNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereJabatanNonDefinitif($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereJabatanNonDefinitif1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereJenisJabatanNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereJenisKawinNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereJenisKelamin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereJenisPegawai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereJumlah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereKabKota($value)
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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereNpwpNomor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai wherePangkat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai wherePendidikanNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereProvinsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereProyeksiJf($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereRangeUmur($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereRiwayatDiklatpim($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereSatuanKerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereStatusKepegwaian($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereTahunLulus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pegawai whereTempatLahirNama($value)
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

