<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalPelajaran extends Model
{
    use HasFactory;

    protected $table = 'jadwal_pelajarans';

    protected $fillable = [
        'tahun_ajaran_id',
        'semester_id',
        'kelas_id',
        'jam_pelajaran_id',
        'mapel_id',
        'ustadz_id',
        'hari',
        'keterangan',
    ];

    // Relasi ke Tahun Ajaran
    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    // Relasi ke Semester
    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class, 'semester_id');
    }

    // Relasi ke Kelas
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    // Relasi ke Jam Pelajaran
    public function jamPelajaran(): BelongsTo
    {
        return $this->belongsTo(JamPelajaran::class, 'jam_pelajaran_id');
    }

    // Relasi ke Mapel
    public function mapel(): BelongsTo
    {
        return $this->belongsTo(Mapel::class, 'mapel_id');
    }

    // Relasi ke Ustadz
    public function ustadz(): BelongsTo
    {
        return $this->belongsTo(Ustadz::class, 'ustadz_id');
    }

    // Accessor: Label Hari
    public function getHariLabelAttribute(): string
    {
        return ucfirst($this->hari);
    }

    // Accessor: Format Waktu dari Jam Pelajaran
    public function getWaktuAttribute(): string
    {
        if (!$this->jamPelajaran) {
            return '-';
        }
        $mulai = $this->jamPelajaran->jam_mulai ? date('H:i', strtotime($this->jamPelajaran->jam_mulai)) : '-';
        $selesai = $this->jamPelajaran->jam_selesai ? date('H:i', strtotime($this->jamPelajaran->jam_selesai)) : '-';
        return "{$mulai} - {$selesai}";
    }

    // Accessor: Jam Ke dari Jam Pelajaran
    public function getJamKeAttribute(): string
    {
        return $this->jamPelajaran?->nama_jam ?? '-';
    }

    // Accessor: Nama Ustadz
    public function getNamaUstadzAttribute(): string
    {
        return $this->ustadz?->nama ?? '-';
    }

    // Accessor: Nama Mapel
    public function getNamaMapelAttribute(): string
    {
        return $this->mapel?->nama_mapel ?? '-';
    }

    // Accessor: Nama Kelas
    public function getNamaKelasAttribute(): string
    {
        return $this->kelas?->nama_kelas ?? '-';
    }

    // Scope: Filter by Semester
    public function scopeBySemester($query, $semesterId)
    {
        return $query->where('semester_id', $semesterId);
    }

    // Scope: Filter by Kelas
    public function scopeByKelas($query, $kelasId)
    {
        return $query->where('kelas_id', $kelasId);
    }

    // Scope: Filter by Hari
    public function scopeByHari($query, $hari)
    {
        return $query->where('hari', $hari);
    }

    /**
     * Bootstrap any application services.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($jadwal) {
            $exists = self::where('ustadz_id', $jadwal->ustadz_id)
                ->where('kelas_id', '!=', $jadwal->kelas_id)
                ->where('hari', $jadwal->hari)
                ->where('jam_pelajaran_id', $jadwal->jam_pelajaran_id)
                ->where('semester_id', $jadwal->semester_id)
                ->exists();

            if ($exists) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'ustadz_id' => ['Ustadz sudah dijadwalkan di waktu yang sama']
                ]);
            }
        });
    }
}
