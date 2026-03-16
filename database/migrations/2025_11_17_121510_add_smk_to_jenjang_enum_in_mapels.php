<?php
// database/migrations/2024_01_02_000000_add_smk_to_jenjang_enum_in_mapels.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE mapels MODIFY COLUMN jenjang ENUM('SD', 'SMP', 'SMA', 'SMK')");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE mapels MODIFY COLUMN jenjang ENUM('SD', 'SMP', 'SMA')");
    }
};