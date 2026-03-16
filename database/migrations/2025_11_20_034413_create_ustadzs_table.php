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
        Schema::create('ustadzs', function (Blueprint $table) {
        $table->id();
        $table->string('nama');
        $table->string('nip')->unique()->nullable(); // Ganti nik menjadi nip
        $table->enum('jenis_kelamin', ['L', 'P']);
        $table->text('alamat');
        $table->string('tempat_lahir')->nullable();
        $table->date('tanggal_lahir')->nullable();
        $table->boolean('status_aktif')->default(true);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ustadzs');
    }
};
