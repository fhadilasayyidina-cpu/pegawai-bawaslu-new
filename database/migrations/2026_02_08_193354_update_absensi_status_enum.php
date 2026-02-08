<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, migrate existing data to new status
        DB::statement("UPDATE absensis SET status = 'Tidak Hadir' WHERE status IN ('Sakit', 'Bolos')");

        // Then modify the enum column
        DB::statement("ALTER TABLE absensis MODIFY COLUMN status ENUM('Hadir', 'Izin', 'Cuti', 'Tidak Hadir') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert the enum column
        DB::statement("ALTER TABLE absensis MODIFY COLUMN status ENUM('Hadir', 'Izin', 'Sakit', 'Cuti', 'Bolos') NOT NULL");

        // Note: Data migration back to Sakit/Bolos is not possible without additional tracking
    }
};
