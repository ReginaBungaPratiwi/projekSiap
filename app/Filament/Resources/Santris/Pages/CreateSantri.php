<?php

namespace App\Filament\Resources\Santris\Pages;

use App\Filament\Resources\Santris\SantriResource;
use App\Models\Santri;
use App\Models\TahunAjaran;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CreateSantri extends CreateRecord
{
    protected static string $resource = SantriResource::class;

    protected function afterCreate(): void
    {
        $santri = $this->record;
        $tahunAjaranAktif = TahunAjaran::getAktif();

        Log::info("=== CREATE SANTRI AFTER CREATE ===");
        Log::info("Santri: {$santri->nama_lengkap}");
        Log::info("Kelas: {$santri->kelas_id}");

        if ($tahunAjaranAktif && $santri->kelas_id) {
            try {
                $santri->riwayatKelas()->firstOrCreate([
                    'kelas_id' => $santri->kelas_id,
                    'tahun_akademik' => $tahunAjaranAktif->tahun_ajaran,
                    'semester' => $tahunAjaranAktif->semester ?? 'ganjil',
                ], [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                Log::info("Riwayat dibuat: Kelas {$santri->kelas_id}, Tahun {$tahunAjaranAktif->tahun_ajaran}");
            } catch (\Exception $e) {
                Log::error("Gagal membuat riwayat kelas: " . $e->getMessage());
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
                    FileUpload::make('excel_file')
                        ->label('Excel File')
                        ->disk('local')
                        ->directory('santri-imports')
                        ->visibility('private')
                        ->maxSize(51200)
                        ->acceptedFileTypes([
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'text/csv'
                        ])
                        ->required(),
                    TextInput::make('kelas_default')
                        ->label('Default Kelas')
                        ->placeholder('10A')
                        ->default('10A'),
                    TextInput::make('tahun_masuk_default')
                        ->label('Tahun Masuk')
                        ->numeric()
                        ->default(now()->year),
                ])
                ->action(function (array $data) {
                    try {
                        Log::info('Import data:', $data);

                        $excelPath = $data['excel_file'];
                        Log::info('Excel path: ' . $excelPath);

                        // Fix: $data['excel_file'] is string (path), not UploadedFile object
                        $fullPath = storage_path('app/private/' . $excelPath);
                        Log::info('Full path: ' . $fullPath);

                        if (!file_exists($fullPath)) {
                            throw new \Exception('File Excel tidak ditemukan: ' . $fullPath);
                        }

                        $importer = new \App\Imports\SantriExcelImport(
                            $data['kelas_default'],
                            $data['tahun_masuk_default']
                        );

                        Excel::import($importer, $fullPath);

                        // Manual riwayat kelas untuk santri baru
                        $tahunAjaranAktif = TahunAjaran::getAktif();
                        if ($tahunAjaranAktif) {
                            Santri::where('created_at', '>=', now()->subMinutes(2))
                                ->whereNotNull('kelas_id')
                                ->chunk(100, function ($santriBatch) use ($tahunAjaranAktif) {
                                    foreach ($santriBatch as $santri) {
                                        $santri->riwayatKelas()->firstOrCreate([
                                            'kelas_id' => $santri->kelas_id,
                                            'tahun_akademik' => $tahunAjaranAktif->tahun_ajaran,
                                            'semester' => $tahunAjaranAktif->semester ?? 'ganjil',
                                        ]);
                                    }
                                });
                        }

                        Notification::make()
                            ->title('Import Berhasil!')
                            ->body('Santri berhasil diimport. Riwayat kelas dibuat otomatis.')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Log::error('Import error: ' . $e->getMessage());
                        Notification::make()
                            ->title('Import Gagal')
                            ->body('Error: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }

                    return redirect(SantriResource::getUrl('index'));
                }),
        ];
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Santri berhasil dibuat';
    }
}
