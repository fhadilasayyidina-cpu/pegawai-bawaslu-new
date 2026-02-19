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
            $table->string('sk_cpns_drive_link')->nullable();
            $table->string('sk_pns_drive_link')->nullable();
            $table->string('sk_kgb_drive_link')->nullable();
            $table->string('karpeg_drive_link')->nullable();
            $table->string('npwp_drive_link')->nullable();
            $table->string('bpjs_drive_link')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pegawais', function (Blueprint $table) {
            $table->dropColumn([
                'sk_cpns_drive_link',
                'sk_pns_drive_link',
                'sk_kgb_drive_link',
                'karpeg_drive_link',
                'npwp_drive_link',
                'bpjs_drive_link',
            ]);
        });
    }
};
