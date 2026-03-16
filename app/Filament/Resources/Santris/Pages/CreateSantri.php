<?php

namespace App\Filament\Resources\Santris\Pages;

use App\Filament\Resources\Santris\SantriResource;
use App\Models\Santri;
use App\Models\TahunAjaran;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log; // ✅ Tambahkan ini

class CreateSantri extends CreateRecord
{
    protected static string $resource = SantriResource::class;

    protected function afterCreate(): void
    {
        $santri = $this->record;
        $tahunAjaranAktif = TahunAjaran::getAktif();

        Log::info("=== CREATE SANTRI AFTER CREATE ===");
        Log::info("Santri: {$santri->nama_lengkap}"); // ✅ Perbaiki: nama_lengkap (bukan name_lengkap)
        Log::info("Kelas: {$santri->kelas_id}");

        if ($tahunAjaranAktif && $santri->kelas_id) {
            try {
                // ✅ Gunakan firstOrCreate untuk menghindari duplicate entry
                $santri->riwayatKelas()->firstOrCreate([
                    'kelas_id' => $santri->kelas_id,
                    'tahun_akademik' => $tahunAjaranAktif->tahun_ajaran, // ✅ Perbaiki: tahun_ajaran (bukan tahun_gjaran)
                    'semester' => $tahunAjaranAktif->semester ?? 'ganjil', // ✅ Perbaiki: 'ganjil' (bukan 'ganji1')
                ], [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                Log::info("Riwayat dibuat: Kelas {$santri->kelas_id}, Tahun {$tahunAjaranAktif->tahun_ajaran}");
                
            } catch (\Exception $e) {
                Log::error("Gagal membuat riwayat kelas: " . $e->getMessage());
                // Tidak throw exception agar create santri tetap berhasil
            }
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Santri berhasil dibuat';
    }
}