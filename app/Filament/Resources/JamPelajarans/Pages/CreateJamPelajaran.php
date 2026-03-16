<?php

namespace App\Filament\Resources\JamPelajarans\Pages;

use App\Filament\Resources\JamPelajarans\JamPelajaranResource;
use Filament\Resources\Pages\CreateRecord;

class CreateJamPelajaran extends CreateRecord
{
    protected static string $resource = JamPelajaranResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Jam pelajaran berhasil ditambahkan';
    }
}
