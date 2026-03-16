<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('santris', function (Blueprint $table) {
            // Hapus kolom 'kelas' yang duplicate dan tidak digunakan
            $table->dropColumn('kelas');
        });
    }

    public function down()
    {
        Schema::table('santris', function (Blueprint $table) {
            // Jika perlu rollback, tambahkan kembali kolom kelas
            $table->string('kelas')->nullable()->after('jenjang');
        });
    }
};