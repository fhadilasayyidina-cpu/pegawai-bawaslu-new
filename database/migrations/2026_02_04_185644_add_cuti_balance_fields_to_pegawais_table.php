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
            $table->unsignedInteger('sisa_cuti_tahun_berjalan')->default(12)->after('status_kepegwaian');
            $table->unsignedInteger('sisa_cuti_tahun_lalu')->default(0)->after('sisa_cuti_tahun_berjalan');
            $table->unsignedInteger('sisa_cuti_dua_tahun_lalu')->default(0)->after('sisa_cuti_tahun_lalu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pegawais', function (Blueprint $table) {
            $table->dropColumn([
                'sisa_cuti_tahun_berjalan',
                'sisa_cuti_tahun_lalu',
                'sisa_cuti_dua_tahun_lalu',
            ]);
        });
    }
};
