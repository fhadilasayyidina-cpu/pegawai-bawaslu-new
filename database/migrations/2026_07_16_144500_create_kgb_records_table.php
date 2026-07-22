<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kgb_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawais')->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('jenis_kgb', 10);
            $table->string('nomor_naskah');
            $table->date('tanggal_naskah');
            $table->date('tmt_baru');
            $table->date('next_kgb_date')->nullable();
            $table->json('data');
            $table->timestamps();

            $table->unique(['pegawai_id', 'nomor_naskah']);
            $table->index(['jenis_kgb', 'tanggal_naskah']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kgb_records');
    }
};
