<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log; // ✅ IMPORT LOG

class Santri extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_lengkap', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'alamat',
        'nis', 'kamar', 'jenjang', 'kelas_id', 'tahun_masuk', 'status',
        'nama_ayah', 'no_hp_ayah', 'pekerjaan_ayah', 'nama_ibu', 'no_hp_ibu', 'pekerjaan_ibu', 'alamat_ortu'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tahun_masuk' => 'integer',
    ];

    // ✅ MODEL EVENTS UNTUK AUTO UPDATE RIWAYAT
    protected static function boot()
    {
        parent::boot();

        // ✅ SYNC STATUS SANTRI KE KELULUSAN
        static::updating(function ($santri) {
            if ($santri->isDirty('status')) {
                $tahunAjaranAktif = TahunAjaran::where('status', true)->first();

                if ($tahunAjaranAktif && $santri->kelas_id) {
                    if ($santri->status === 'lulus') {
                        // Santri diubah jadi lulus → buat/update kelulusan jadi lulus
                        Kelulusan::withoutEvents(function () use ($santri, $tahunAjaranAktif) {
                            Kelulusan::updateOrCreate(
                                [
                                    'santri_id' => $santri->id,
                                    'kelas_id' => $santri->kelas_id,
                                    'tahun_ajaran_id' => $tahunAjaranAktif->id,
                                ],
                                [
                                    'status' => 'lulus',
                                ]
                            );
                        });
                        Log::info("Sync: Santri {$santri->nama_lengkap} status lulus → Kelulusan diupdate ke lulus");
                    } else {
                        // Santri diubah jadi aktif/nonaktif → update kelulusan jika ada
                        Kelulusan::withoutEvents(function () use ($santri, $tahunAjaranAktif) {
                            $kelulusan = Kelulusan::where('santri_id', $santri->id)
                                ->where('kelas_id', $santri->kelas_id)
                                ->where('tahun_ajaran_id', $tahunAjaranAktif->id)
                                ->first();

                            if ($kelulusan && $kelulusan->status === 'lulus') {
                                $kelulusan->update(['status' => 'belum_ditentukan']);
                            }
                        });
                        Log::info("Sync: Santri {$santri->nama_lengkap} status {$santri->status} → Kelulusan diupdate ke belum_ditentukan");
                    }
                }
            }

            // Cek jika kelas_id berubah
            if ($santri->isDirty('kelas_id')) {
                $kelasLama = $santri->getOriginal('kelas_id');
                $kelasBaru = $santri->kelas_id;
                
                Log::info("=== KELAS BERUBAH ===");
                Log::info("Santri: {$santri->nama_lengkap}");
                Log::info("Kelas Lama: {$kelasLama} -> Kelas Baru: {$kelasBaru}");

                $tahunAjaranAktif = TahunAjaran::where('status', true)->first();
                if ($tahunAjaranAktif) {
                    $tahunAkademik = $tahunAjaranAktif->tahun_awal . '/' . $tahunAjaranAktif->tahun_akhir;
                    $semester = $tahunAjaranAktif->semester ?? 'ganjil';
                    
                    Log::info("Tahun Akademik: {$tahunAkademik}, Semester: {$semester}");

                    // Hapus riwayat di tahun akademik aktif
                    $deleted = $santri->riwayatKelas()
                        ->where('tahun_akademik', $tahunAkademik)
                        ->delete();
                        
                    Log::info("Riwayat lama dihapus: {$deleted} records");

                    // Buat riwayat baru untuk kelas baru
                    $newRiwayat = $santri->riwayatKelas()->create([
                        'kelas_id' => $kelasBaru,
                        'tahun_akademik' => $tahunAkademik,
                        'semester' => $semester,
                    ]);
                    
                    Log::info("Riwayat baru dibuat ID: {$newRiwayat->id}");
                    Log::info("RIWAYAT DIUPDATE: {$kelasBaru} di {$tahunAkademik}");
                } else {
                    Log::error("Tidak ada tahun ajaran aktif!");
                }
            }
        });

        // ✅ BUAT RIWAYAT AWAL SAAT SANTRI BARU
        static::created(function ($santri) {
            if ($santri->kelas_id) {
                Log::info("=== BUAT RIWAYAT AWAL ===");
                Log::info("Santri Baru: {$santri->nama_lengkap}, Kelas: {$santri->kelas_id}");

                $tahunAjaranAktif = TahunAjaran::where('status', true)->first();
                if ($tahunAjaranAktif) {
                    $tahunAkademik = $tahunAjaranAktif->tahun_awal . '/' . $tahunAjaranAktif->tahun_akhir;
                    $semester = $tahunAjaranAktif->semester ?? 'ganjil';
                    
                    $riwayat = $santri->riwayatKelas()->create([
                        'kelas_id' => $santri->kelas_id,
                        'tahun_akademik' => $tahunAkademik,
                        'semester' => $semester,
                    ]);
                    
                    Log::info("Riwayat awal dibuat ID: {$riwayat->id}");
                } else {
                    Log::error("Tidak ada tahun ajaran aktif untuk buat riwayat awal!");
                }
            }
        });
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function kelulusans(): HasMany
    {
        return $this->hasMany(Kelulusan::class, 'santri_id');
    }

    public function riwayatKelas(): HasMany
    {
        return $this->hasMany(SantriKelas::class, 'santri_id')
                    ->orderBy('tahun_akademik', 'desc')
                    ->orderBy('semester', 'desc');
    }

    public function getNamaAttribute()
    {
        return $this->nama_lengkap;
    }

    public function getKelasPadaTahun($tahunAkademik)
    {
        return $this->riwayatKelas()
                    ->where('tahun_akademik', $tahunAkademik)
                    ->first();
    }

    /**
     * Get ketidakhadiran data untuk semester tertentu
     *
     * @param int $kelasId ID kelas
     * @param Semester $semester Semester object dengan tahun_ajaran_id dan semester
     * @return array Array dengan keys: 'sakit', 'izin', 'alpha' dan nilai countnya
     */
    public function getKetidakhadiranSemester($kelasId, $semester)
    {
        // ✅ Ambil semua absensi untuk santri ini di kelas dan semester tertentu
        $absensi = Absensi::where('santri_id', $this->id)
            ->where('kelas_id', $kelasId)
            ->where('tahun_ajaran_id', $semester->tahun_ajaran_id)
            ->where('semester', $semester->semester)
            ->orderBy('tanggal', 'desc')
            ->get();
        
        // ✅ Debug untuk melihat data
        Log::info("=== GET KETIDAKHADIRAN SEMESTER ===");
        Log::info("Santri: {$this->nama_lengkap} (ID: {$this->id})");
        Log::info("Kelas ID: {$kelasId}");
        Log::info("Tahun Ajaran ID: {$semester->tahun_ajaran_id}, Semester: {$semester->semester}");
        Log::info("Total data absensi: " . $absensi->count());
        
        // ✅ Kelompokkan per tanggal dan ambil status terakhir (karena bisa diupdate)
        $statusPerHari = [];
        foreach ($absensi as $a) {
            $tanggal = $a->tanggal->format('Y-m-d');
            // Simpan status terakhir untuk hari itu (karena sudah diurutkan descending)
            if (!isset($statusPerHari[$tanggal])) {
                $statusPerHari[$tanggal] = $a->status;
            }
        }
        
        Log::info("Hari dengan absensi: " . count($statusPerHari));
        
        // ✅ Hitung jumlah berdasarkan status
        $sakit = 0;
        $izin = 0;
        $alpha = 0;
        
        foreach ($statusPerHari as $status) {
            switch ($status) {
                case 'sakit':
                    $sakit++;
                    break;
                case 'izin':
                    $izin++;
                    break;
                case 'alpha':
                    $alpha++;
                    break;
            }
        }

        $result = [
            'sakit' => $sakit,
            'izin' => $izin,
            'alpha' => $alpha,
        ];
        
        Log::info("Hasil: Sakit: {$sakit}, Izin: {$izin}, Alpha: {$alpha}");
        
        return $result;
    }

    /**
     * Versi alternatif yang lebih sederhana dengan unique()
     */
    public function getKetidakhadiranSemesterSimple($kelasId, $semester)
    {
        $absensi = Absensi::where('santri_id', $this->id)
            ->where('kelas_id', $kelasId)
            ->where('tahun_ajaran_id', $semester->tahun_ajaran_id)
            ->where('semester', $semester->semester)
            ->orderBy('tanggal', 'desc')
            ->get()
            ->unique('tanggal')
            ->pluck('status');
        
        return [
            'sakit' => $absensi->filter(fn($s) => $s == 'sakit')->count(),
            'izin' => $absensi->filter(fn($s) => $s == 'izin')->count(),
            'alpha' => $absensi->filter(fn($s) => $s == 'alpha')->count(),
        ];
    }
}