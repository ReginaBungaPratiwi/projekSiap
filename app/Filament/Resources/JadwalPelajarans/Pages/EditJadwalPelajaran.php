<?php

namespace App\Filament\Resources\JadwalPelajarans\Pages;

use App\Filament\Resources\JadwalPelajarans\JadwalPelajaranResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJadwalPelajaran extends EditRecord
{
    protected static string $resource = JadwalPelajaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Schedule successfully updated';
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Check unique constraint excluding current record
        $exists = \App\Models\JadwalPelajaran::where('ustadz_id', $data['ustadz_id'])
            ->where('kelas_id', '!=', $data['kelas_id'])
            ->where('hari', $data['hari'])
            ->where('jam_pelajaran_id', $data['jam_pelajaran_id'])
            ->where('semester_id', $data['semester_id'])
            ->where('id', '!=', $this->record->id)
            ->exists();

        if ($exists) {
            \Filament\Notifications\Notification::make()
                ->warning()
                ->title('Duplikat Jadwal')
                ->body('Ustadz sudah mengajar di waktu yang sama. Satu ustadz tidak bisa mengajar kelas berbeda bersamaan.')
                ->persistent()
                ->send();

            throw \Illuminate\Validation\ValidationException::withMessages([
                'ustadz_id' => ['Ustadz sudah dijadwalkan di waktu yang sama']
            ]);
        }

        return $data;
    }
}
