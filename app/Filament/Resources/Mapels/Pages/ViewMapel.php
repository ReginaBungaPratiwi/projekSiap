<?php

namespace App\Filament\Resources\Mapels\Pages;

use App\Filament\Resources\Mapels\MapelResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMapel extends ViewRecord
{
    protected static string $resource = MapelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Edit'),
                
            Actions\Action::make('back')
                ->label('Kembali ke Daftar Mapel')
                ->url(MapelResource::getUrl('index'))
                ->color('gray'),
        ];
    }
}