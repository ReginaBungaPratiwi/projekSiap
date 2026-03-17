<?php

namespace App\Filament\Resources\Absensis\Pages;

use App\Filament\Resources\Absensis\AbsensiResource;
use App\Models\Kelas;
use App\Models\Absensi;
use Filament\Resources\Pages\Page;

class DetailAbsensi extends Page
{
    protected static string $resource = AbsensiResource::class;

    protected string $view = 'filament.resources.absensis.pages.detail-absensi';

    public Kelas $kelas;
    public string $tanggal;
    public array $absensiData = [];

    public function mount($record): void
    {
        $this->kelas = Kelas::with(['santris', 'waliKelas'])->findOrFail($record);
        $this->tanggal = request()->get('tanggal', now()->toDateString());
        $this->loadData();
    }

    public function loadData(): void
    {
        $santris = $this->kelas->santris;
        $absensis = Absensi::where('kelas_id', $this->kelas->id)
            ->whereDate('tanggal', $this->tanggal)
            ->with('ustadz')
            ->get()
            ->keyBy('santri_id');
        
        foreach ($santris as $santri) {
            $absensi = $absensis[$santri->id] ?? null;

            $this->absensiData[$santri->id] = [
                'santri_id' => $santri->id,
                'nama' => $santri->nama,
                'nis' => $santri->nis ?? '-',
                'status' => $absensi?->status,
                'status_label' => $absensi ? ucfirst($absensi->status) : 'Belum',
                'keterangan' => $absensi?->keterangan ?? '-',
                'waktu' => $absensi?->created_at ? $absensi->created_at->format('H:i') : '-',
                'diinput' => $absensi?->ustadz?->nama ?? '-',
            ];
        }
    }

    public function updatedTanggal($value)
    {
        $this->tanggal = $value;
        $this->loadData();
    }

    public function getStatistik()
    {
        $total = count($this->absensiData);
        $hadir = collect($this->absensiData)->where('status', 'hadir')->count();
        $sakit = collect($this->absensiData)->where('status', 'sakit')->count();
        $izin = collect($this->absensiData)->where('status', 'izin')->count();
        $alpha = collect($this->absensiData)->where('status', 'alpha')->count();
        $belum = $total - ($hadir + $sakit + $izin + $alpha);
        
        return compact('total', 'hadir', 'sakit', 'izin', 'alpha', 'belum');
    }

    public function getTitle(): string
    {
        return "Lihat Absensi: {$this->kelas->nama_kelas}";
    }
}