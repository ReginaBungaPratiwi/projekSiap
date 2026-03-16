<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Nilai extends Model
{
    protected $fillable = [
        'santri_id',
        'mapel_id',
        'kelas_id',
        'tahun_ajaran_id',
        'semester_id',
        'ustadz_id',
        'nilai_harian',
        'nilai_uts',
        'nilai_uas',
        'nilai_praktik',
        'nilai_akhir',
        'nilai_huruf',
        'catatan',
    ];

    protected $casts = [
        'nilai_harian' => 'decimal:2',
        'nilai_uts' => 'decimal:2',
        'nilai_uas' => 'decimal:2',
        'nilai_praktik' => 'decimal:2',
        'nilai_akhir' => 'decimal:2',
    ];

    // Model events for auto-calculation
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($nilai) {
            $nilai->calculateNilaiAkhir();
            $nilai->calculateNilaiHuruf();
        });
    }

    // Auto-calculate nilai_akhir
    public function calculateNilaiAkhir(): void
    {
        $harian = $this->nilai_harian ?? 0;
        $uts = $this->nilai_uts ?? 0;
        $uas = $this->nilai_uas ?? 0;
        $praktik = $this->nilai_praktik ?? 0;

        $this->nilai_akhir = round(
            (0.20 * $harian) +
            (0.30 * $uts) +
            (0.40 * $uas) +
            (0.10 * $praktik),
            2
        );
    }

    // Auto-calculate nilai_huruf based on nilai_akhir
    public function calculateNilaiHuruf(): void
    {
        $akhir = $this->nilai_akhir;

        if ($akhir >= 90) {
            $this->nilai_huruf = 'A';
        } elseif ($akhir >= 80) {
            $this->nilai_huruf = 'B';
        } elseif ($akhir >= 70) {
            $this->nilai_huruf = 'C';
        } elseif ($akhir >= 60) {
            $this->nilai_huruf = 'D';
        } else {
            $this->nilai_huruf = 'E';
        }
    }

    // Check if passed KKM
    public function isPassedKkm(): bool
    {
        // Coba cari KKM spesifik untuk kelas ini
        $kkm = Kkm::where('mapel_id', $this->mapel_id)
            ->where('kelas_id', $this->kelas_id)
            ->where('tahun_ajaran_id', $this->tahun_ajaran_id)
            ->where('semester_id', $this->semester_id)
            ->first();

        // Jika tidak ada, coba cari KKM tanpa filter kelas
        if (!$kkm) {
            $kkm = Kkm::where('mapel_id', $this->mapel_id)
                ->where('tahun_ajaran_id', $this->tahun_ajaran_id)
                ->where('semester_id', $this->semester_id)
                ->first();
        }

        if (!$kkm) {
            return true; // If no KKM set, assume passed
        }

        return $this->nilai_akhir >= $kkm->nilai_kkm;
    }

    /**
     * Get KKM value for this nilai
     */
    public function getKkmValue(): ?int
    {
        $kkm = Kkm::where('mapel_id', $this->mapel_id)
            ->where('kelas_id', $this->kelas_id)
            ->where('tahun_ajaran_id', $this->tahun_ajaran_id)
            ->where('semester_id', $this->semester_id)
            ->first();

        if (!$kkm) {
            $kkm = Kkm::where('mapel_id', $this->mapel_id)
                ->where('tahun_ajaran_id', $this->tahun_ajaran_id)
                ->where('semester_id', $this->semester_id)
                ->first();
        }

        return $kkm?->nilai_kkm;
    }

    // Relationships
    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class);
    }

    public function mapel(): BelongsTo
    {
        return $this->belongsTo(Mapel::class);
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

    /**
     * Convert number to Indonesian words (terbilang)
     */
    public function getNilaiTerbilangAttribute(): string
    {
        return self::numberToWords((int) round($this->nilai_akhir));
    }

    /**
     * Get status kemajuan belajar
     */
    public function getKemajuanBelajarAttribute(): string
    {
        return $this->isPassedKkm() ? 'TERLAMPAUI' : 'BELUM TERCAPAI';
    }

    /**
     * Static helper to convert number to Indonesian words
     */
    public static function numberToWords(int $number): string
    {
        $satuan = ['', 'SATU', 'DUA', 'TIGA', 'EMPAT', 'LIMA', 'ENAM', 'TUJUH', 'DELAPAN', 'SEMBILAN'];
        $belasan = ['SEPULUH', 'SEBELAS', 'DUA BELAS', 'TIGA BELAS', 'EMPAT BELAS', 'LIMA BELAS', 'ENAM BELAS', 'TUJUH BELAS', 'DELAPAN BELAS', 'SEMBILAN BELAS'];
        $puluhan = ['', '', 'DUA PULUH', 'TIGA PULUH', 'EMPAT PULUH', 'LIMA PULUH', 'ENAM PULUH', 'TUJUH PULUH', 'DELAPAN PULUH', 'SEMBILAN PULUH'];

        if ($number < 0 || $number > 100) {
            return (string) $number;
        }

        if ($number == 0) {
            return 'NOL';
        }

        if ($number == 100) {
            return 'SERATUS';
        }

        if ($number < 10) {
            return $satuan[$number];
        }

        if ($number < 20) {
            return $belasan[$number - 10];
        }

        $puluh = (int) floor($number / 10);
        $sisa = $number % 10;

        if ($sisa == 0) {
            return $puluhan[$puluh];
        }

        return $puluhan[$puluh] . ' ' . $satuan[$sisa];
    }
}
