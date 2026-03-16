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
            $table->foreignId('tahun_ajaran_id')->nullable()->constrained()->onDelete('cascade')->after('ustadz_id');
            $table->enum('semester', ['ganjil', 'genap'])->nullable()->after('tahun_ajaran_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            $table->dropForeignIdFor('tahun_ajaran_id');
            $table->dropColumn(['tahun_ajaran_id', 'semester']);
        });
    }
};
