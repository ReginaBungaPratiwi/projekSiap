<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TahunAjaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'tahun_awal',
        'tahun_akhir', 
        'semester',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    protected static function booted()
    {
        static::saving(function ($record) {
            // Jika record ini diaktifkan
            if ($record->status == true) {
                // Nonaktifkan semua tahun ajaran lain
                static::where('id', '!=', $record->id)
                    ->update(['status' => false]);
            }
        });
    }

    // ✅ ACCESSOR: Format tahun ajaran untuk display
    public function getTahunAjaranAttribute(): string
    {
        return $this->tahun_awal . '/' . $this->tahun_akhir;
    }

    // ✅ ACCESSOR: Format tahun akademik (compatibility dengan kode sebelumnya)
    public function getTahunAkademikAttribute(): string
    {
        return $this->tahun_awal . '/' . $this->tahun_akhir;
    }

    // ✅ ACCESSOR: Format lengkap dengan semester
    public function getTahunAjaranLengkapAttribute(): string
    {
        $semester = $this->semester == 'ganjil' ? 'Ganjil' : 'Genap';
        return "{$this->tahun_awal}/{$this->tahun_akhir} - Semester {$semester}";
    }

    public function kelas(): HasMany
    {
        return $this->hasMany(Kelas::class, 'tahun_ajaran_id');
    }

    public function semesters(): HasMany
    {
        return $this->hasMany(Semester::class, 'tahun_ajaran_id');
    }

    // ✅ RELASI BARU: Ke SantriKelas (Riwayat Kelas)
    public function santriKelas(): HasMany
    {
        return $this->hasMany(SantriKelas::class, 'tahun_akademik', 'tahun_ajaran');
    }

    public function scopeAktif($query)
    {
        return $query->where('status', true);
    }

    // ✅ METHOD BARU: Mendapatkan tahun ajaran aktif
    public static function getAktif()
    {
        return static::aktif()->first();
    }

    // ✅ METHOD BARU: Validasi apakah ada tahun ajaran aktif
    public static function hasAktif()
    {
        return static::aktif()->exists();
    }
}