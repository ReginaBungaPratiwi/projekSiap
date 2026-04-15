<?php

namespace App\Filament\Resources\TahunAjarans\Pages;

use App\Filament\Resources\TahunAjarans\TahunAjaranResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTahunAjaran extends CreateRecord
{
    protected static string $resource = TahunAjaranResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $tahunSekarang = (int) date('Y');
        $data['tahun_awal'] = $tahunSekarang;
        $data['tahun_akhir'] = $tahunSekarang + 1;
        $data['status'] = true;
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Tahun ajaran berhasil ditambahkan';
    }
}
