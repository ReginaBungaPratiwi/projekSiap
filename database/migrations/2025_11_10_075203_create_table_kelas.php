<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelas', 50);
            $table->enum('jenjang', ['SD', 'SMP', 'SMA', 'SMK']); // ✅ TAMBAH 'SMK' // ✅ Tetap enum untuk validasi
            $table->string('jurusan', 50); // ✅ Ubah jadi string (bukan enum) biar flexible
            $table->string('wali_kelas', 100)->nullable();
            $table->string('tahun_ajaran', 9);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};