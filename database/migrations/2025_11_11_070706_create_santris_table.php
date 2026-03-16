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
            
            // IDENTITAS PRIBADI
            $table->string('nama_lengkap');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->text('alamat');
            
            // AKADEMIK  
            $table->string('nis')->unique();
            $table->enum('jenjang', ['SD', 'SMP', 'SMA', 'SMK']);
            $table->string('kelas');
            $table->string('kamar')->nullable();
            $table->year('tahun_masuk');
            $table->boolean('status')->default(true);
            
            // ORANG TUA
            $table->string('nama_ayah');
            $table->string('no_hp_ayah')->nullable();
            $table->string('pekerjaan_ayah')->nullable();
            $table->string('nama_ibu');
            $table->string('no_hp_ibu')->nullable();
            $table->string('pekerjaan_ibu')->nullable();
            $table->text('alamat_ortu')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('santris');
    }
};