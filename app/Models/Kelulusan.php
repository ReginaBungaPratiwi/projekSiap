<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kelulusan extends Model
{
    use HasFactory;

    protected $table = 'kelulusans';

    protected $fillable = [
        'santri_id',
        'kelas_id',
        'tahun_ajaran_id',
        'status',
        'catatan',
    ];

    /**
     * Status kelulusan
     */
    public const STATUS_LULUS = 'lulus';
    public const STATUS_TIDAK_LULUS = 'tidak_lulus';
    public const STATUS_BELUM_DITENTUKAN = 'belum_ditentukan';

    /**
     * Get all status options
     */
    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_LULUS => 'Lulus',
            self::STATUS_TIDAK_LULUS => 'Tidak Lulus',
            self::STATUS_BELUM_DITENTUKAN => 'Belum di tentukan',
        ];
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return self::getStatusOptions()[$this->status] ?? $this->status;
    }

    /**
     * Relationship to Santri
     */
    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class);
    }

    /**
     * Relationship to Kelas
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    /**
     * Relationship to TahunAjaran
     */
    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    /**
     * Scope by Kelas
     */
    public function scopeByKelas($query, $kelasId)
    {
        return $query->where('kelas_id', $kelasId);
    }

    /**
     * Scope by Tahun Ajaran
     */
    public function scopeByTahunAjaran($query, $tahunAjaranId)
    {
        return $query->where('tahun_ajaran_id', $tahunAjaranId);
    }

    /**
     * Check if santri lulus
     */
    public function isLulus(): bool
    {
        return $this->status === self::STATUS_LULUS;
    }

    /**
     * Check if santri tidak lulus
     */
    public function isTidakLulus(): bool
    {
        return $this->status === self::STATUS_TIDAK_LULUS;
    }

    /**
     * Check if status belum ditentukan
     */
    public function isBelumDitentukan(): bool
    {
        return $this->status === self::STATUS_BELUM_DITENTUKAN;
    }
}
