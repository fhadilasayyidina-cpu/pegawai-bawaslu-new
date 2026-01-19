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
            $table->string('nip_baru', 255)->nullable();
            $table->string('nip_lama', 255)->nullable();
            $table->string('nama', 255)->nullable();
            $table->string('gelar_depan', 255)->nullable();
            $table->string('gelar_blk', 255)->nullable();
            $table->text('keterangan')->nullable();
            $table->string('tempat_lahir_nama', 255)->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->string('jenis_kelamin', 50)->nullable();
            $table->string('agama_nama', 255)->nullable();
            $table->string('jenis_kawin_nama', 255)->nullable();
            $table->string('nik', 255)->nullable();
            $table->string('nomor_hp', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('email_gov', 255)->nullable();
            $table->text('alamat')->nullable();
            $table->string('npwp_nomor', 255)->nullable();
            $table->string('bpjs', 255)->nullable();
            $table->string('kartu_pegawai', 255)->nullable();
            $table->string('nomor_sk_cpns', 255)->nullable();
            $table->date('tgl_sk_cpns')->nullable();
            $table->date('tmt_cpns')->nullable();
            $table->string('nomor_sk_pns', 255)->nullable();
            $table->date('tgl_sk_pns')->nullable();
            $table->date('tmt_pns')->nullable();
            $table->string('gol_awal_nama', 255)->nullable();
            $table->string('gol_nama', 255)->nullable();
            $table->date('tmt_golongan')->nullable();
            $table->string('jenis_jabatan_nama', 255)->nullable();
            $table->text('jabatan_nama')->nullable();
            $table->date('tmt_jabatan')->nullable();
            $table->text('unor_nama')->nullable();
            $table->text('instansi_induk_nama')->nullable();
            $table->string('eselon', 50)->nullable();
            $table->string('kelompok_jabatan', 255)->nullable();
            $table->string('nm_kelompok_jabatan', 255)->nullable();
            $table->string('range_umur', 50)->nullable();
            $table->string('kelas_jabatan', 50)->nullable();
            $table->string('keterangan_status', 255)->nullable();
            $table->string('tingkat_pendidikan_nama', 255)->nullable();
            $table->string('satuan_kerja', 255)->nullable();
            $table->string('provinsi', 255)->nullable();
            $table->string('kab_kota', 255)->nullable();
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
