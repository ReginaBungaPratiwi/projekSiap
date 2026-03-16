<?php
// database/migrations/[timestamp]_add_jurusan_id_to_kelas_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            // Tambah kolom jurusan_id
            $table->foreignId('jurusan_id')->nullable()->constrained('jurusans')->onDelete('set null');
            
            // Opsional: hapus kolom jurusan lama jika ingin migrasi penuh
            // $table->dropColumn('jurusan');
        });
    }

    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropForeign(['jurusan_id']);
            $table->dropColumn('jurusan_id');
        });
    }
};