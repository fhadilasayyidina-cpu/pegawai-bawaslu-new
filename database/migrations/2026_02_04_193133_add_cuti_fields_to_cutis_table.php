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
        Schema::table('cutis', function (Blueprint $table) {
            // Fields untuk Cuti Sakit
            $table->string('status_dokter')->nullable()->after('nomor_surat_edaran');
            $table->string('nama_dokter')->nullable()->after('status_dokter');
            $table->string('nomor_surat_dokter')->nullable()->after('nama_dokter');

            // Fields untuk Cuti Melahirkan
            $table->string('jenis_melahirkan')->nullable()->after('nomor_surat_dokter'); // normal/caesar
            $table->date('tanggal_perkiraan_lahir')->nullable()->after('jenis_melahirkan');

            // Fields untuk Cuti di Luar Tanggungan Negara
            $table->boolean('tanpa_gaji')->default(false)->after('tanggal_perkiraan_lahir');
            $table->text('alasan_luar_tanggungan')->nullable()->after('tanpa_gaji');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cutis', function (Blueprint $table) {
            $table->dropColumn([
                'status_dokter',
                'nama_dokter',
                'nomor_surat_dokter',
                'jenis_melahirkan',
                'tanggal_perkiraan_lahir',
                'tanpa_gaji',
                'alasan_luar_tanggungan',
            ]);
        });
    }
};
