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
            $table->string('sk_golongan_awal_drive_link')->nullable();
            $table->string('sk_golongan_terakhir_drive_link')->nullable();
            $table->string('sk_jabatan_drive_link')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pegawais', function (Blueprint $table) {
            $table->dropColumn([
                'sk_golongan_awal_drive_link',
                'sk_golongan_terakhir_drive_link',
                'sk_jabatan_drive_link',
            ]);
        });
    }
};
