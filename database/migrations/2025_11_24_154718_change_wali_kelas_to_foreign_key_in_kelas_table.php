<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            // Hapus kolom wali_kelas string
            $table->dropColumn('wali_kelas');
            
            // Tambah kolom wali_kelas_id sebagai foreign key
            $table->foreignId('wali_kelas_id')
                  ->nullable()
                  ->constrained('ustadzs')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropForeign(['wali_kelas_id']);
            $table->dropColumn('wali_kelas_id');
            
            // Kembalikan kolom string
            $table->string('wali_kelas')->nullable();
        });
    }
};