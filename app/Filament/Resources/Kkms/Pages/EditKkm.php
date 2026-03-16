<?php

namespace App\Filament\Resources\Kkms\Pages;

use App\Filament\Resources\Kkms\KkmResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKkm extends EditRecord
{
    protected static string $resource = KkmResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()->label('View'),
            Actions\DeleteAction::make()->label('Hapus'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'KKM berhasil diperbarui';
    }
}
