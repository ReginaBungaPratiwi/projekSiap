<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nilai_sikaps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santris')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajarans')->onDelete('cascade');
            $table->foreignId('semester_id')->constrained('semesters')->onDelete('cascade');
            $table->foreignId('ustadz_id')->nullable()->constrained('ustadzs')->onDelete('set null');

            // Nilai Sikap Spiritual (KI-1)
            $table->enum('sikap_spiritual', ['SB', 'B', 'C', 'K'])->default('B')->comment('Sangat Baik, Baik, Cukup, Kurang');
            $table->text('catatan_spiritual')->nullable();

            // Nilai Sikap Sosial (KI-2)
            $table->enum('sikap_sosial', ['SB', 'B', 'C', 'K'])->default('B')->comment('Sangat Baik, Baik, Cukup, Kurang');
            $table->text('catatan_sosial')->nullable();

            // Penilaian Akhlak secara umum
            $table->enum('akhlak', ['SB', 'B', 'C', 'K'])->default('B');
            $table->text('catatan_akhlak')->nullable();

            // Kehadiran/Kedisiplinan
            $table->unsignedTinyInteger('kehadiran_persen')->default(100)->comment('Persentase kehadiran');
            $table->text('catatan_kedisiplinan')->nullable();

            $table->timestamps();

            // Unique constraint: satu santri satu nilai per semester
            $table->unique(['santri_id', 'tahun_ajaran_id', 'semester_id'], 'nilai_sikap_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilai_sikaps');
    }
};
