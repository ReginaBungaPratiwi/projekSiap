<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Semester extends Model
{
    use HasFactory;

    protected $fillable = [
        'tahun_ajaran_id',
        'semester',
        'tanggal_mulai',
        'tanggal_selesai',
        'keterangan',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    protected static function booted()
    {
        static::saving(function ($record) {
            // Jika record ini diaktifkan, nonaktifkan semester lain
            if ($record->status == true) {
                static::where('id', '!=', $record->id)
                    ->update(['status' => false]);
            }
        });
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    // Accessor: Label semester (Ganjil/Genap)
    public function getSemesterLabelAttribute(): string
    {
        return $this->semester === 'ganjil' ? 'Ganjil' : 'Genap';
    }

    // Accessor: Format lengkap (Tahun Ajaran + Semester)
    public function getNamaLengkapAttribute(): string
    {
        $tahunAjaran = $this->tahunAjaran?->tahun_ajaran ?? '-';
        return "{$tahunAjaran} - Semester {$this->semester_label}";
    }

    // Scope: Filter semester aktif
    public function scopeAktif($query)
    {
        return $query->where('status', true);
    }

    // Method: Mendapatkan semester aktif
    public static function getAktif()
    {
        return static::aktif()->first();
    }

    // Method: Validasi apakah ada semester aktif
    public static function hasAktif()
    {
        return static::aktif()->exists();
    }
}
