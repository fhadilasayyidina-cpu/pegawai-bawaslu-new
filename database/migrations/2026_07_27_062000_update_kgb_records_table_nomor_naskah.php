<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kgb_records', function (Blueprint $table) {
            $table->unique(['pegawai_id', 'tmt_baru']);
            $table->dropUnique(['pegawai_id', 'nomor_naskah']);
            $table->string('nomor_naskah')->default('-')->change();
        });

        DB::table('kgb_records')
            ->where('nomor_naskah', 'like', 'KGB-%')
            ->update(['nomor_naskah' => '-']);
    }

    public function down(): void
    {
        Schema::table('kgb_records', function (Blueprint $table) {
            $table->dropUnique(['pegawai_id', 'tmt_baru']);
            $table->unique(['pegawai_id', 'nomor_naskah']);
        });
    }
};
