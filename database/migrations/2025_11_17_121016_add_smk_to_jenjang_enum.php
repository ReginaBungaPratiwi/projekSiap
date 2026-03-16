<?php
// database/migrations/2024_01_01_000000_add_smk_to_jenjang_enum.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Untuk MySQL
        DB::statement("ALTER TABLE kelas MODIFY COLUMN jenjang ENUM('SD', 'SMP', 'SMA', 'SMK')");
    }

    public function down(): void  // ✅ PERBAIKI: function bukan void
    {
        DB::statement("ALTER TABLE kelas MODIFY COLUMN jenjang ENUM('SD', 'SMP', 'SMA')");
    }
};