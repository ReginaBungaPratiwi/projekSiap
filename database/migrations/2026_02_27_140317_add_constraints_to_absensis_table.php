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
        Schema::table('absensis', function (Blueprint $table) {
            // Add more specific unique constraint: santri tidak bisa input 2x di kelas yang sama dalam 1 hari
            // (Menjaga agar santri hanya bisa input 1x per hari per kelas)
            $table->unique(['santri_id', 'kelas_id', 'tanggal'], 'unique_santri_kelas_tanggal');

            // Add indexes untuk query performance
            $table->index(['kelas_id', 'tanggal']);
            $table->index(['tanggal', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            $table->dropUnique('unique_santri_kelas_tanggal');
            $table->dropIndex(['kelas_id', 'tanggal']);
            $table->dropIndex(['tanggal', 'status']);
        });
    }
};
