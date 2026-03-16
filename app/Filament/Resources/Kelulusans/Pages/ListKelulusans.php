<?php

namespace App\Filament\Resources\Kelulusans\Pages;

use App\Filament\Resources\Kelulusans\KelulusanResource;
use App\Models\Kelas;
use App\Models\Santri;
use App\Models\TahunAjaran;
use App\Models\User;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListKelulusans extends ListRecords
{
    protected static string $resource = KelulusanResource::class;

    public function mount(): void
    {
        parent::mount();

        /** @var User|null $user */
        $user = Auth::user();

        // Jika ustadz wali kelas, redirect langsung ke list santri kelasnya
        if ($user && $user->hasRole('ustadz') && $user->ustadz_id) {
            // Filter hanya kelas akhir (12, 9, 6) yang dia walikan
            $kelasAkhirPatterns = KelulusanResource::getKelasAkhirPatterns();

            // Prioritaskan kelas akhir yang memiliki santri
            $kelas = Kelas::where('wali_kelas_id', $user->ustadz_id)
                ->where(function ($q) use ($kelasAkhirPatterns) {
                    foreach ($kelasAkhirPatterns as $pattern) {
                        $q->orWhere('nama_kelas', 'LIKE', $pattern);
                    }
                })
                ->whereHas('santris')
                ->first();

            // Jika tidak ada kelas akhir dengan santri, ambil kelas akhir pertama saja
            if (!$kelas) {
                $kelas = Kelas::where('wali_kelas_id', $user->ustadz_id)
                    ->where(function ($q) use ($kelasAkhirPatterns) {
                        foreach ($kelasAkhirPatterns as $pattern) {
                            $q->orWhere('nama_kelas', 'LIKE', $pattern);
                        }
                    })
                    ->first();
            }

            if ($kelas) {
                $tahunAjaran = TahunAjaran::where('status', true)->first();

                if ($tahunAjaran) {
                    $this->redirect(KelulusanResource::getUrl('list-santri', [
                        'record' => $kelas->id,
                        'tahun_ajaran' => $tahunAjaran->id,
                    ]));
                }
            }
        }
    }

    public function getTitle(): string
    {
        return 'Kelulusan Santri';
    }

    public function getSubheading(): ?string
    {
        return 'List Daftar Kelas';
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
