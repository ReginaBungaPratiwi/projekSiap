<?php
// app/Models/Jurusan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Jurusan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_jurusan',
        'deskripsi',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    // ✅ RELASI KE MAPEL - PASTIKAN NAMA 'mapels'
    public function mapels(): HasMany
    {
        return $this->hasMany(Mapel::class, 'jurusan_id');
    }

    // ✅ RELASI KE KELAS
    public function kelas(): HasMany
    {
        return $this->hasMany(Kelas::class, 'jurusan_id');
    }

    public function getNamaJurusanFormattedAttribute(): string
    {
        return $this->nama_jurusan . ($this->status ? '' : ' (Non Aktif)');
    }
}