<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ustadz extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'nip',
        'jenis_kelamin',
        'alamat',
        'tempat_lahir',
        'tanggal_lahir',
        'status_aktif',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'status_aktif' => 'boolean',
    ];

    /**
     * Scope untuk ustadz aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status_aktif', true);
    }

    /**
     * Accessor untuk nama lengkap dengan gelar (jika perlu)
     */
    public function getNamaLengkapAttribute()
    {
        return "Ust. {$this->nama}";
    }

    /**
     * Accessor untuk usia (jika tanggal_lahir ada)
     */
    public function getUsiaAttribute()
    {
        if (!$this->tanggal_lahir) {
            return null;
        }

        return $this->tanggal_lahir->age;
    }

    // ✅ RELASI BARU: MANY-TO-MANY KE MAPEL
    public function mataPelajarans(): BelongsToMany
    {
        return $this->belongsToMany(Mapel::class, 'mapel_ustadz');
    }

    // ✅ RELASI: ONE-TO-MANY KE JADWAL PELAJARAN
    public function jadwalPelajarans(): HasMany
    {
        return $this->hasMany(JadwalPelajaran::class, 'ustadz_id');
    }

    /**
     * Dapatkan semua kelas yang diajara oleh ustadz ini
     * (BUKAN berdasarkan wali kelas, tapi based on jadwal pelajaran)
     */
    public function getKelasYangDiajar()
    {
        return Kelas::distinct()
            ->join('jadwal_pelajarans', 'kelas.id', '=', 'jadwal_pelajarans.kelas_id')
            ->where('jadwal_pelajarans.ustadz_id', $this->id)
            ->get();
    }

    /**
     * Dapatkan kelas-kelas yang BISA di-input absensi oleh ustadz ini
     * Kombinasi dari:
     * 1. Kelas yang dia ajar (via jadwal pelajaran)
     * 2. Kelas yang dia jadi wali kelas
     */
    public function getKelasUntukAbsensi()
    {
        // Kelas yang diajara via jadwal pelajaran
        $kelasYangDiajari = Kelas::distinct()
            ->join('jadwal_pelajarans', 'kelas.id', '=', 'jadwal_pelajarans.kelas_id')
            ->where('jadwal_pelajarans.ustadz_id', $this->id)
            ->pluck('kelas.id')
            ->toArray();

        // Kelas yang menjadi wali kelas
        $kelasWaliKelas = Kelas::where('wali_kelas_id', $this->id)
            ->pluck('id')
            ->toArray();

        // Gabungkan kedua array (dengan unique)
        $allKelasIds = array_unique(array_merge($kelasYangDiajari, $kelasWaliKelas));

        // Return hasil gabungan
        return Kelas::whereIn('id', $allKelasIds)->get();
    }

    /**
     * Cek apakah ustadz bisa input absensi di kelas tertentu
     */
    public function canInputAbsensiDiKelas(Kelas $kelas): bool
    {
        // Cek apakah mengajar di kelas ini
        $isTeaching = JadwalPelajaran::where('ustadz_id', $this->id)
            ->where('kelas_id', $kelas->id)
            ->exists();

        // Cek apakah menjadi wali kelas
        $isWaliKelas = $kelas->wali_kelas_id === $this->id;

        return $isTeaching || $isWaliKelas;
    }
}