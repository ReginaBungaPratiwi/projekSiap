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
        Schema::table('nilai_sikaps', function (Blueprint $table) {
            // Hapus kolom lama jika ada
            if (Schema::hasColumn('nilai_sikaps', 'sikap_spiritual')) {
                $table->dropColumn('sikap_spiritual');
            }
            if (Schema::hasColumn('nilai_sikaps', 'catatan_spiritual')) {
                $table->dropColumn('catatan_spiritual');
            }
            if (Schema::hasColumn('nilai_sikaps', 'sikap_sosial')) {
                $table->dropColumn('sikap_sosial');
            }
            if (Schema::hasColumn('nilai_sikaps', 'catatan_sosial')) {
                $table->dropColumn('catatan_sosial');
            }
            if (Schema::hasColumn('nilai_sikaps', 'akhlak')) {
                $table->dropColumn('akhlak');
            }
            if (Schema::hasColumn('nilai_sikaps', 'catatan_akhlak')) {
                $table->dropColumn('catatan_akhlak');
            }
            if (Schema::hasColumn('nilai_sikaps', 'kehadiran_persen')) {
                $table->dropColumn('kehadiran_persen');
            }
            if (Schema::hasColumn('nilai_sikaps', 'catatan_kedisiplinan')) {
                $table->dropColumn('catatan_kedisiplinan');
            }
        });

        Schema::table('nilai_sikaps', function (Blueprint $table) {
            // Tambah kolom baru sesuai screenshot
            $table->char('disiplin', 1)->default('A')->after('ustadz_id')->comment('A=Sangat Baik, B=Baik, C=Cukup, D=Kurang');
            $table->char('tanggung_jawab', 1)->default('A')->after('disiplin');
            $table->char('kejujuran', 1)->default('A')->after('tanggung_jawab');
            $table->char('sopan_santun', 1)->default('A')->after('kejujuran')->comment('Sopan Santun / Adab');
            $table->char('kepedulian', 1)->default('A')->after('sopan_santun')->comment('Kepedulian / Kerja Sama');
            $table->text('catatan')->nullable()->after('kepedulian')->comment('Catatan Pembinaan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nilai_sikaps', function (Blueprint $table) {
            // Hapus kolom baru
            $table->dropColumn(['disiplin', 'tanggung_jawab', 'kejujuran', 'sopan_santun', 'kepedulian', 'catatan']);
        });

        Schema::table('nilai_sikaps', function (Blueprint $table) {
            // Kembalikan kolom lama
            $table->enum('sikap_spiritual', ['SB', 'B', 'C', 'K'])->default('B')->after('ustadz_id');
            $table->text('catatan_spiritual')->nullable()->after('sikap_spiritual');
            $table->enum('sikap_sosial', ['SB', 'B', 'C', 'K'])->default('B')->after('catatan_spiritual');
            $table->text('catatan_sosial')->nullable()->after('sikap_sosial');
            $table->enum('akhlak', ['SB', 'B', 'C', 'K'])->default('B')->after('catatan_sosial');
            $table->text('catatan_akhlak')->nullable()->after('akhlak');
            $table->unsignedTinyInteger('kehadiran_persen')->default(100)->after('catatan_akhlak');
            $table->text('catatan_kedisiplinan')->nullable()->after('kehadiran_persen');
        });
    }
};
