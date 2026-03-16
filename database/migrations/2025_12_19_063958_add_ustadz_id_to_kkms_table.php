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
        Schema::table('kkms', function (Blueprint $table) {
            $table->foreignId('ustadz_id')->nullable()->after('semester_id')->constrained('ustadzs')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kkms', function (Blueprint $table) {
            $table->dropForeign(['ustadz_id']);
            $table->dropColumn('ustadz_id');
        });
    }
};
