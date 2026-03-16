<?php

namespace App\Filament\Resources\Absensis\Pages;

use App\Filament\Resources\Absensis\AbsensiResource;
use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Ustadz;
use App\Models\Semester;
use Filament\Resources\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Tambahkan untuk debugging

class InputAbsensi extends Page
{
    protected static string $resource = AbsensiResource::class;

    protected string $view = 'filament.resources.absensis.pages.input-absensi';

    public Kelas $kelas;
    public string $tanggal;
    public array $absensiData = [];
    public array $statistik = [];

    public function mount($record): void
    {
        $this->kelas = Kelas::with(['santris', 'waliKelas'])->findOrFail($record);

        // Check authorization: hanya ustadz yang bisa input
        $user = Auth::user();
        if (!$user) {
            Notification::make()
                ->title('Akses Ditolak')
                ->body('Anda harus login terlebih dahulu.')
                ->danger()
                ->send();
            $this->redirect(AbsensiResource::getUrl('index'));
            return;
        }

        // Cek apakah user adalah ustadz
        $ustadz = Ustadz::find($user->ustadz_id);

        if (!$ustadz) {
            Notification::make()
                ->title('Akses Ditolak')
                ->body('Hanya ustadz yang dapat melakukan input absensi.')
                ->danger()
                ->send();
            $this->redirect(AbsensiResource::getUrl('index'));
            return;
        }

        // Check apakah ustadz berhak input di kelas ini
        $kelasYangBisaDiinput = $ustadz->getKelasUntukAbsensi()->pluck('id')->toArray();
        
        if (!in_array($this->kelas->id, $kelasYangBisaDiinput)) {
            Notification::make()
                ->title('Akses Ditolak')
                ->body('Anda tidak berhak menginput absensi di kelas ini. Anda hanya bisa menginput absensi untuk kelas yang Anda ajar.')
                ->danger()
                ->send();
            $this->redirect(AbsensiResource::getUrl('index'));
            return;
        }

        // Ambil tanggal dari request atau default hari ini
        $this->tanggal = request()->get('tanggal', now()->toDateString());

        $this->loadData();
        $this->hitungStatistik();
    }

    public function loadData(): void
    {
        // Ambil data absensi yang sudah ada
        $existingAbsensis = Absensi::where('kelas_id', $this->kelas->id)
            ->whereDate('tanggal', $this->tanggal)
            ->get()
            ->keyBy('santri_id');

        // Ambil semua santri di kelas ini
        $santriList = $this->kelas->santris;

        foreach ($santriList as $santri) {
            $existing = $existingAbsensis[$santri->id] ?? null;

            $this->absensiData[$santri->id] = [
                'santri_id' => $santri->id,
                'nis' => $santri->nis ?? '-',
                'nama' => $santri->nama,
                'status' => $existing?->status ?? 'hadir',
                'keterangan' => $existing?->keterangan ?? '',
            ];
        }
    }

    public function hitungStatistik(): void
    {
        $total = count($this->absensiData);
        $hadir = collect($this->absensiData)->where('status', 'hadir')->count();
        $sakit = collect($this->absensiData)->where('status', 'sakit')->count();
        $izin = collect($this->absensiData)->where('status', 'izin')->count();
        $alpha = collect($this->absensiData)->where('status', 'alpha')->count();
        
        $this->statistik = [
            'total' => $total,
            'hadir' => $hadir,
            'sakit' => $sakit,
            'izin' => $izin,
            'alpha' => $alpha,
            'sudah' => $hadir + $sakit + $izin + $alpha,
        ];
    }

    public function updatedAbsensiData(): void
    {
        $this->hitungStatistik();
    }

    public function save(): void
    {
        // Validasi
        if (empty($this->absensiData)) {
            Notification::make()
                ->title('Tidak ada data')
                ->body('Tidak ada santri yang dapat diinput absensinya.')
                ->warning()
                ->send();
            return;
        }

        $user = Auth::user();

        // Cari ustadz berdasarkan ustadz_id user
        $ustadz = Ustadz::find($user->ustadz_id);

        if (!$ustadz) {
            Notification::make()
                ->title('Error')
                ->body('Data ustadz tidak ditemukan.')
                ->danger()
                ->send();
            return;
        }

        // Ambil semester aktif
        $semesterAktif = Semester::where('status', true)->first();
        
        if (!$semesterAktif) {
            Notification::make()
                ->title('Error')
                ->body('Tidak ada semester aktif. Hubungi administrator.')
                ->danger()
                ->send();
            return;
        }

        try {
            DB::beginTransaction();

            foreach ($this->absensiData as $santriId => $data) {
                // ✅ Cek apakah sudah ada absensi untuk santri ini di tanggal yang sama
                $existingAbsensi = Absensi::where('santri_id', $santriId)
                    ->whereDate('tanggal', $this->tanggal)
                    ->first();
                
                if ($existingAbsensi) {
                    // Jika sudah ada, update statusnya
                    $existingAbsensi->update([
                        'status' => $data['status'],
                        'keterangan' => $data['keterangan'] ?? null,
                        'tahun_ajaran_id' => $semesterAktif->tahun_ajaran_id,
                        'semester' => $semesterAktif->semester,
                    ]);
                    
                    Log::info("Update absensi: Santri ID {$santriId}, Status: {$data['status']}");
                } else {
                    // Jika belum ada, buat baru
                    Absensi::create([
                        'santri_id' => $santriId,
                        'kelas_id' => $this->kelas->id,
                        'ustadz_id' => $ustadz->id,
                        'tahun_ajaran_id' => $semesterAktif->tahun_ajaran_id,
                        'semester' => $semesterAktif->semester,
                        'tanggal' => $this->tanggal,
                        'status' => $data['status'],
                        'keterangan' => $data['keterangan'] ?? null,
                    ]);
                    
                    Log::info("Buat absensi baru: Santri ID {$santriId}, Status: {$data['status']}");
                }
            }

            DB::commit();

            Notification::make()
                ->title('Absensi berhasil disimpan')
                ->body('Data absensi untuk kelas ' . $this->kelas->nama_kelas . ' tanggal ' . \Carbon\Carbon::parse($this->tanggal)->format('d/m/Y') . ' telah disimpan.')
                ->success()
                ->send();

            // Redirect ke halaman detail
            $this->redirect(AbsensiResource::getUrl('detail', [
                'record' => $this->kelas->id,
                'tanggal' => $this->tanggal
            ]));

        } catch (\Exception $e) {
            DB::rollBack();

            Notification::make()
                ->title('Gagal menyimpan')
                ->body('Terjadi kesalahan: ' . $e->getMessage())
                ->danger()
                ->send();
            
            Log::error('Error save absensi: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
        }
    }

    public function getTitle(): string
    {
        return 'Input Absensi: ' . $this->kelas->nama_kelas;
    }
}