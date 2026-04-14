<?php

namespace App\Filament\Resources\Santris\Pages;

use App\Filament\Resources\Santris\SantriResource;
use App\Models\Santri;
use App\Models\TahunAjaran;
use Filament\Actions;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;

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

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('import-santri')
                ->label('Import Excel Santri')
                ->color('success')
                ->icon('heroicon-o-arrow-up-tray')
                ->form([
                    \Filament\Forms\Components\FileUpload::make('excel_file')
                        ->label('Excel File')
                        ->acceptedFileTypes(['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'text/csv'])
                        ->directory('santri-imports')
                        ->visibility('private')
                        ->required(),
                    \Filament\Forms\Components\TextInput::make('kelas_default')
                        ->label('Default Kelas')
                        ->placeholder('10A'),
                    \Filament\Forms\Components\TextInput::make('tahun_masuk_default')
                        ->label('Tahun Masuk')
                        ->numeric()
                        ->default(now()->year),
                ])
                ->action(function (array $data) {
                    $excelPath = $data['excel_file'];
                    $importer = new \App\Imports\SantriExcelImport(
                        $data['kelas_default'],
                        $data['tahun_masuk_default']
                    );

                    \Maatwebsite\Excel\Facades\Excel::import($importer, storage_path('app/private/' . $excelPath));

                    \Filament\Notifications\Notification::make()
                        ->title('Import Berhasil')
                        ->body('Data santri berhasil diimport. Kembali ke daftar santri.')
                        ->success()
                        ->send();

                    return redirect(SantriResource::getUrl('index'));
                }),
        ];
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Santri berhasil dibuat';
    }
}
