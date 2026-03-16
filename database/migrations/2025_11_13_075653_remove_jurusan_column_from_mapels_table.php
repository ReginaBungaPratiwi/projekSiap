<?php
// database/migrations/[timestamp]_remove_jurusan_column_from_mapels_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // TAMBAHKAN SAFETY CHECK INI
        if (Schema::hasColumn('mapels', 'jurusan')) {
            Schema::table('mapels', function (Blueprint $table) {
                $table->dropColumn('jurusan');
            });
        }
        // Jika column tidak ada, tidak melakukan apa-apa
    }

    public function down(): void
    {
        Schema::table('mapels', function (Blueprint $table) {
            $table->string('jurusan', 50)->nullable();
        });
    }
};