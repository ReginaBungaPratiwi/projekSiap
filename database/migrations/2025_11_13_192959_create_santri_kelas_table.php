<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('santri_kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained()->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained()->onDelete('cascade');
            $table->integer('tahun_akademik');
            $table->enum('semester', ['ganjil', 'genap']);
            $table->timestamps();
            
            // Unique constraint untuk menghindari duplikasi
            $table->unique(['santri_id', 'kelas_id', 'tahun_akademik', 'semester']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('santri_kelas');
    }
};