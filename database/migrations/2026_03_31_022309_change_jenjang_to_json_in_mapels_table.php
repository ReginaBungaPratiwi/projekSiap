<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Ubah enum ke VARCHAR dulu (agar data existing tidak error)
        DB::statement("ALTER TABLE mapels MODIFY jenjang VARCHAR(255) NOT NULL");

        // Step 2: Konversi data lama: string → JSON array
        $mapels = DB::table('mapels')->get();
        foreach ($mapels as $mapel) {
            $value = $mapel->jenjang;
            if (!str_starts_with($value, '[')) {
                DB::table('mapels')
                    ->where('id', $mapel->id)
                    ->update(['jenjang' => json_encode([$value])]);
            }
        }

        // Step 3: Ubah ke JSON
        DB::statement("ALTER TABLE mapels MODIFY jenjang JSON NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Ubah ke VARCHAR dulu
        DB::statement("ALTER TABLE mapels MODIFY jenjang VARCHAR(255) NOT NULL");

        // Konversi kembali: ambil value pertama dari array
        $mapels = DB::table('mapels')->get();
        foreach ($mapels as $mapel) {
            $decoded = json_decode($mapel->jenjang, true);
            $first = is_array($decoded) ? ($decoded[0] ?? 'SD') : $mapel->jenjang;
            DB::table('mapels')
                ->where('id', $mapel->id)
                ->update(['jenjang' => $first]);
        }

        DB::statement("ALTER TABLE mapels MODIFY jenjang ENUM('SD','SMP','SMA','SMK') NOT NULL");
    }
};
