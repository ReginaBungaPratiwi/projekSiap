<?php

namespace App\Filament\Resources\JamPelajarans\Pages;

use App\Filament\Resources\JamPelajarans\JamPelajaranResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJamPelajaran extends EditRecord
{
    protected static string $resource = JamPelajaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()->label('Delete'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Jam pelajaran berhasil diupdate';
    }
}
