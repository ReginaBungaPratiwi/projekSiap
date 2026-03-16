<?php

namespace App\Filament\Resources\Ustadzs\Pages;

use App\Filament\Resources\Ustadzs\UstadzResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUstadz extends CreateRecord
{
    protected static string $resource = UstadzResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Ustadz berhasil ditambahkan';
    }
}