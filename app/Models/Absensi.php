<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'santri_id',
        'kelas_id',
        'ustadz_id',
        'tahun_ajaran_id',
        'semester',
        'tanggal',
        'status',
        'keterangan',
    ];

    // ✅ TAMBAHKAN CASTING UNTUK TANGGAL
    protected $casts = [
        'tanggal' => 'date', // Ini akan mengubah string menjadi Carbon object
    ];

    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function ustadz(): BelongsTo
    {
        return $this->belongsTo(Ustadz::class);
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }
}