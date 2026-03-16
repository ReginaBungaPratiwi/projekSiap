<?php
// database/migrations/2025_01_01_000001_change_tahun_akademik_to_string_in_santri_kelas_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('santri_kelas', function (Blueprint $table) {
            $table->string('tahun_akademik')->change();
        });
    }

    public function down(): void
    {
        Schema::table('santri_kelas', function (Blueprint $table) {
            $table->integer('tahun_akademik')->change();
        });
    }
};