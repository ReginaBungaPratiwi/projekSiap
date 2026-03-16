<?php

namespace App\Filament\Resources\TahunAjarans\Pages;

use App\Filament\Resources\TahunAjarans\TahunAjaranResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTahunAjaran extends ViewRecord
{
    protected static string $resource = TahunAjaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->label('Edit'),
            Actions\Action::make('back')
                ->label('Kembali ke Daftar')
                ->url(TahunAjaranResource::getUrl('index'))
                ->color('gray'),
        ];
    }
}