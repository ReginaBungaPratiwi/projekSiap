<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('kelas', function (Blueprint $table) {
            // Hapus kolom tahun_ajaran
            if (Schema::hasColumn('kelas', 'tahun_ajaran')) {
                $table->dropColumn('tahun_ajaran');
            }
        });
    }

    public function down()
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->string('tahun_ajaran', 9)->nullable();
        });
    }
};