<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kelas extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kelas',
        'jenjang',
        'jurusan_id',
        'wali_kelas_id', // ✅ UBAH: dari 'wali_kelas' ke 'wali_kelas_id'
    ];

    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id');
    }

    // ✅ TAMBAH: Relasi ke Ustadz (Wali Kelas)
    public function waliKelas(): BelongsTo
    {
        return $this->belongsTo(Ustadz::class, 'wali_kelas_id');
    }

    public function santriKelas(): HasMany
    {
        return $this->hasMany(SantriKelas::class, 'kelas_id');
    }

    public function santris(): HasMany
    {
        return $this->hasMany(Santri::class, 'kelas_id');
    }

    public function jadwalPelajarans(): HasMany
    {
        return $this->hasMany(JadwalPelajaran::class, 'kelas_id');
    }

    public function absensis(): HasMany
    {
        return $this->hasMany(Absensi::class, 'kelas_id');
    }

    public function getNamaJurusanAttribute()
    {
        return $this->jurusan->nama_jurusan ?? '-';
    }

    // ✅ PERBAIKAN: Accessor yang benar (hindari recursive)
    public function getNamaWaliKelasAttribute()
    {
        return $this->waliKelas ? "Ust. {$this->waliKelas->nama}" : null;
    }
}