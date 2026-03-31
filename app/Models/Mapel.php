<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Mapel extends Model
{
    use HasFactory;

    protected $table = 'mapels';

    protected $fillable = [
        'nama_mapel',
        'jurusan_id',
        'jenjang',
        // ❌ HAPUS 'pengampu' dari sini jika masih ada
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'jenjang' => 'array',
    ];

    // ✅ RELASI KE JURUSAN
    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id');
    }

    // ✅ RELASI BARU: MANY-TO-MANY KE USTADZ (PENGGAMPU)
    public function pengampu(): BelongsToMany
    {
        return $this->belongsToMany(Ustadz::class, 'mapel_ustadz');
    }

    // ✅ ACCESSOR untuk nama jurusan
    public function getNamaJurusanAttribute(): string
    {
        return $this->jurusan ? $this->jurusan->nama_jurusan : '-';
    }

    // ✅ ACCESSOR untuk handle data lama (jika ada kolom pengampu di tabel)
    public function getPengampuAttribute($value)
    {
        // Jika $value adalah string (data lama), return sebagai string
        if (is_string($value)) {
            return $value;
        }
        
        // Jika relasi sudah diload, return collection
        if ($this->relationLoaded('pengampu')) {
            return $this->getRelation('pengampu');
        }
        
        return $value;
    }
}