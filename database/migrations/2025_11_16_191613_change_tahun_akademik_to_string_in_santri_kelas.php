<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('santri_kelas', function (Blueprint $table) {
            // Ubah dari integer ke string untuk format 2020/2021
            $table->string('tahun_akademik', 9)->change(); // 2020/2021
        });
    }

    public function down(): void
    {
        Schema::table('santri_kelas', function (Blueprint $table) {
            $table->integer('tahun_akademik')->change();
        });
    }
};