<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();

            $table->foreignId('santri_id')->constrained()->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained()->onDelete('cascade');
            $table->foreignId('ustadz_id')->constrained()->onDelete('cascade');

            $table->date('tanggal');
            $table->enum('status', ['hadir','izin','sakit','alpha']);

            $table->timestamps();

            $table->unique(['santri_id','tanggal']); 
            // supaya tidak dobel absen di tanggal yang sama
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};

