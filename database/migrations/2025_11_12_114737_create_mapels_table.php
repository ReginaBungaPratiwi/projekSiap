<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mapels', function (Blueprint $table) {
            $table->id();
            $table->string('nama_mapel');
            $table->string('jurusan', 50);
            $table->foreignId('jurusan_id')->nullable(); // ✅ TANPA CONSTRAINED()
            $table->enum('jenjang', ['SD', 'SMP', 'SMA', 'SMK']); // ✅ TAMBAH 'SMK'
            $table->string('pengampu')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mapels');
    }
};