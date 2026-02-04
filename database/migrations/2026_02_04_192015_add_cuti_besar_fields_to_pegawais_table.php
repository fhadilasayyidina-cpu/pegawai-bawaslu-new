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
        Schema::table('pegawais', function (Blueprint $table) {
            $table->unsignedInteger('jumlah_cuti_besar_diambil')->default(0)->after('sisa_cuti_dua_tahun_lalu');
            $table->date('tanggal_cuti_besar_terakhir')->nullable()->after('jumlah_cuti_besar_diambil');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pegawais', function (Blueprint $table) {
            $table->dropColumn([
                'jumlah_cuti_besar_diambil',
                'tanggal_cuti_besar_terakhir',
            ]);
        });
    }
};
