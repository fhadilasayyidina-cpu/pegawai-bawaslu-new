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
        Schema::create('cutis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawais')->onDelete('cascade');
            $table->string('nomor_surat');
            $table->string('jenis_cuti')->default('tahunan');
            $table->text('alasan');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->integer('lama_hari');
            $table->text('keterangan')->nullable();
            $table->string('nama_kepala_sekretariat');
            $table->string('nip_kepala_sekretariat')->nullable();
            $table->string('nama_sekjen');
            $table->string('nip_sekjen')->nullable();
            $table->string('nomor_surat_edaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cutis');
    }
};
