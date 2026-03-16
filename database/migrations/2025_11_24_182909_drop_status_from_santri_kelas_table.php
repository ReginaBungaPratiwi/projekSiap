<?php
// database/migrations/2025_01_01_000000_drop_status_from_santri_kelas_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('santri_kelas', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }

    public function down(): void
    {
        Schema::table('santri_kelas', function (Blueprint $table) {
            $table->enum('status', ['sebelumnya', 'saat_ini'])->default('sebelumnya');
        });
    }
};