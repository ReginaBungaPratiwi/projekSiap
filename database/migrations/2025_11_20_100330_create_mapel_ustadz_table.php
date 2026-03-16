<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mapel_ustadz', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mapel_id')->constrained()->onDelete('cascade');
            $table->foreignId('ustadz_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['mapel_id', 'ustadz_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mapel_ustadz');
    }
};