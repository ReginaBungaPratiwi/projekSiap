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
        Schema::create('nilais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santris')->onDelete('cascade');
            $table->foreignId('mapel_id')->constrained('mapels')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajarans')->onDelete('cascade');
            $table->foreignId('semester_id')->constrained('semesters')->onDelete('cascade');
            $table->foreignId('ustadz_id')->nullable()->constrained('ustadzs')->onDelete('set null');

            // Nilai components
            $table->decimal('nilai_harian', 5, 2)->nullable();
            $table->decimal('nilai_uts', 5, 2)->nullable();
            $table->decimal('nilai_uas', 5, 2)->nullable();
            $table->decimal('nilai_praktik', 5, 2)->nullable();

            // Calculated fields
            $table->decimal('nilai_akhir', 5, 2)->default(0);
            $table->char('nilai_huruf', 1)->default('E');

            $table->text('catatan')->nullable();
            $table->timestamps();

            // Unique constraint: satu nilai per santri per mapel per tahun ajaran per semester
            $table->unique(['santri_id', 'mapel_id', 'tahun_ajaran_id', 'semester_id'], 'nilai_unique');

            // Indexes for performance
            $table->index(['kelas_id', 'semester_id']);
            $table->index('ustadz_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilais');
    }
};
