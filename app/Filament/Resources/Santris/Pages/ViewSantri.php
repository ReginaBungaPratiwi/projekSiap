<?php

namespace App\Filament\Resources\Santris\Pages;

use App\Filament\Resources\Santris\SantriResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSantri extends ViewRecord
{
    protected static string $resource = SantriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Tombol Edit
            Actions\EditAction::make()
                ->label('Edit'),

            // Tombol Kembali ke daftar santri
            Actions\Action::make('back')
                ->label('Kembali ke Daftar Santri')
                ->url(SantriResource::getUrl('index'))
                ->color('gray'),
        ];
    }
}
