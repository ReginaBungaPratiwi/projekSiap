<?php

namespace App\Filament\Resources\Kkms\Pages;

use App\Filament\Resources\Kkms\KkmResource;
use Filament\Resources\Pages\CreateRecord;

class CreateKkm extends CreateRecord
{
    protected static string $resource = KkmResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'KKM berhasil dibuat';
    }
}
