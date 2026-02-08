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
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained()->onDelete('cascade');
            $table->date('tanggal');
            $table->enum('status', ['Hadir', 'Izin', 'Sakit', 'Cuti', 'Bolos']);
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->unique(['pegawai_id', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
