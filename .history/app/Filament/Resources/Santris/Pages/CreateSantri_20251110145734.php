<?php

namespace App\Filament\Resources\SantriResource\Pages;

use App\Filament\Resources\SantriResource;
use App\Filament\Resources\SantriResource\Schemas\SantriForm;
use Filament\Resources\Pages\CreateRecord;

class CreateSantri extends CreateRecord
{
    protected static string $resource = SantriResource::class;

    // UBAH MENJADI public
    public function getFormSchema(): array
    {
        return SantriForm::schema($this->form);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Santri berhasil ditambahkan!';
    }
}