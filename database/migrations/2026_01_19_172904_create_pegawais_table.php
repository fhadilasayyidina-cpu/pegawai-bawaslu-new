<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pegawais', function (Blueprint $table) {
            $table->id();

            // Identitas
            $table->string('nip_baru', 255)->nullable();
            $table->string('nip_lama', 255)->nullable();
            $table->string('nama', 255)->nullable();
            $table->string('gelar_depan', 255)->nullable();
            $table->string('gelar_blk', 255)->nullable();
            $table->string('jenis_kelamin', 50)->nullable();
            $table->string('gol_darah', 10)->nullable();
            $table->string('agama_nama', 255)->nullable();
            $table->string('jenis_kawin_nama', 255)->nullable();
            $table->string('nik', 255)->nullable();
            $table->string('nomor_hp', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('email_gov', 255)->nullable();
            $table->text('alamat')->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->integer('usia')->nullable();

            // Dokumen / Administrasi
            $table->string('npwp_nomor', 255)->nullable();
            $table->string('bpjs', 255)->nullable();
            $table->string('kartu_pegawai', 255)->nullable();
            $table->string('nomor_sk_cpns', 255)->nullable();
            $table->date('tgl_sk_cpns')->nullable();
            $table->date('tmt_cpns')->nullable();
            $table->string('nomor_sk_pns', 255)->nullable();
            $table->date('tgl_sk_pns')->nullable();
            $table->date('tmt_pns')->nullable();
            $table->string('no_sk_dpk_penugasan_kontrak', 255)->nullable();
            $table->date('tgl_sk_dpk_penugasan_kontrak')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('keterangan_status', 255)->nullable();

            // Golongan & Jabatan
            $table->string('gol_awal_nama', 255)->nullable();
            $table->string('gol_nama', 255)->nullable();
            $table->date('tmt_golongan')->nullable();
            $table->integer('mkgol')->nullable();
            $table->string('jenis_jabatan_nama', 255)->nullable();
            $table->text('jabatan_nama')->nullable();
            $table->date('tmt_jabatan')->nullable();
            $table->text('jabatan_non_definitif')->nullable();
            $table->text('jabatan_non_definitif_1')->nullable();
            $table->integer('mkjab')->nullable();
            $table->integer('jumlah')->nullable();
            $table->string('kelas', 50)->nullable();
            $table->string('kelas_jabatan', 50)->nullable();
            $table->string('kelompok_jabatan', 255)->nullable();
            $table->string('nm_kelompok_jabatan', 255)->nullable();
            $table->string('nama_kelompok_jabatan', 255)->nullable();
            $table->string('pangkat', 255)->nullable();
            $table->string('proyeksi_jf', 255)->nullable();

            // Pendidikan
            $table->string('tingkat_pendidikan_nama', 255)->nullable();
            $table->string('pendidikan_nama', 255)->nullable();
            $table->string('tahun_lulus', 10)->nullable();
            $table->string('riwayat_diklatpim', 255)->nullable();

            // Unit / Organisasi
            $table->string('satuan_kerja', 255)->nullable();
            $table->string('unit_kerja', 255)->nullable();
            $table->string('unit_organisasi', 255)->nullable();
            $table->string('unor_nama', 255)->nullable();
            $table->string('instansi_induk_nama', 255)->nullable();
            $table->string('eselon', 50)->nullable();
            $table->string('divisi', 255)->nullable();
            $table->string('ukm', 255)->nullable();
            $table->string('range_umur', 50)->nullable();

            // Lokasi
            $table->string('provinsi', 255)->nullable();
            $table->string('kab_kota', 255)->nullable();

            // Status pegawai
            $table->string('jenis_pegawai', 255)->nullable();
            $table->string('status_kepegwaian', 255)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawais');
    }
};
