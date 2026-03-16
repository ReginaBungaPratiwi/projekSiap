<?php

namespace App\Filament\Resources\Santris\Pages;

use App\Filament\Resources\Santris\SantriResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSantri extends EditRecord
{
    protected static string $resource = SantriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Santri berhasil diperbarui';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}