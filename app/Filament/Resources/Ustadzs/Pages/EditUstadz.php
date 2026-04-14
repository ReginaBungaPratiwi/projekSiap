<?php

namespace App\Filament\Resources\Ustadzs\Pages;

use App\Filament\Resources\Ustadzs\UstadzResource;
use Filament\Resources\Pages\EditRecord;

class EditUstadz extends EditRecord
{
    protected static string $resource = UstadzResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Ustadz berhasil diperbarui';
    }
}