<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Method 1: Safe check before altering
        $results = DB::select("SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'santris' 
            AND COLUMN_NAME = 'status'");
        
        if (!empty($results)) {
            $columnType = $results[0]->COLUMN_TYPE;
            
            // Jika masih boolean/tinyint, ubah ke enum
            if (str_contains($columnType, 'tinyint')) {
                DB::statement("ALTER TABLE santris MODIFY status ENUM('aktif', 'nonaktif', 'lulus') DEFAULT 'aktif' NOT NULL");
                
                // Update existing data
                DB::table('santris')->where('status', '1')->update(['status' => 'aktif']);
                DB::table('santris')->where('status', '0')->update(['status' => 'nonaktif']);
            }
        }
    }

    public function down()
    {
        // Optional: kita bisa kosongkan jika tidak perlu rollback
    }
};