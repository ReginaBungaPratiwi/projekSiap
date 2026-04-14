<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SantriKelas extends Model
{
    use HasFactory;

    protected $table = 'santri_kelas';

    protected $fillable = [
        'santri_id',
        'kelas_id',
        'tahun_akademik',
        'semester',
    ];

    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }
}