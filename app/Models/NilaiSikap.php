<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NilaiSikap extends Model
{
    protected $fillable = [
        'santri_id',
        'kelas_id',
        'tahun_ajaran_id',
        'semester_id',
        'ustadz_id',
        'disiplin',
        'tanggung_jawab',
        'kejujuran',
        'sopan_santun',
        'kepedulian',
        'catatan',
    ];

    // Opsi nilai sikap
    public const NILAI_OPTIONS = [
        'A' => 'A',
        'B' => 'B',
        'C' => 'C',
        'D' => 'D',
    ];

    // Label untuk nilai sikap
    public const NILAI_LABELS = [
        'A' => 'Sangat Baik',
        'B' => 'Baik',
        'C' => 'Cukup',
        'D' => 'Kurang',
    ];

    // Relationships
    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function ustadz(): BelongsTo
    {
        return $this->belongsTo(Ustadz::class);
    }

    // Scopes
    public function scopeByKelas($query, $kelasId)
    {
        return $query->where('kelas_id', $kelasId);
    }

    public function scopeBySemester($query, $semesterId)
    {
        return $query->where('semester_id', $semesterId);
    }

    public function scopeBySantri($query, $santriId)
    {
        return $query->where('santri_id', $santriId);
    }

    public function scopeByTahunAjaran($query, $tahunAjaranId)
    {
        return $query->where('tahun_ajaran_id', $tahunAjaranId);
    }

    // Helper untuk label sikap
    public static function getLabelSikap(?string $nilai): string
    {
        if (!$nilai) {
            return '-';
        }
        return self::NILAI_LABELS[$nilai] ?? '-';
    }
}
