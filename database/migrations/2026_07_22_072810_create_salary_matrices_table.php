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
        Schema::create('salary_matrices', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_pegawai');
            $table->string('golongan');
            $table->integer('mkg_tahun');
            $table->bigInteger('gaji_pokok');
            $table->timestamps();

            $table->unique(['jenis_pegawai', 'golongan', 'mkg_tahun']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_matrices');
    }
};
