<?php

namespace App\Filament\Resources\SantriResource\Pages;

use App\Filament\Resources\SantriResource;
use App\Filament\Resources\SantriResource\Schemas\SantriForm;
use Filament\Resources\Pages\EditRecord;

class EditSantri extends EditRecord
{
    protected static string $resource = SantriResource::class;

    protected function getFormSchema(): array
    {
        return SantriForm::schema();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Data santri berhasil diperbarui!';
    }
}