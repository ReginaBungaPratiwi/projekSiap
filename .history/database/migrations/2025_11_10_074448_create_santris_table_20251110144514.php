<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('santris', function (Blueprint $table) {
            $table->id();
            $table->string('nama_santri');
            $table->string('nis')->unique();
            $table->string('kelas');
            $table->enum('jenjang', ['SD', 'SMP', 'SMA', 'SMK']);
            $table->year('tahun_masuk');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('santris');
    }
};