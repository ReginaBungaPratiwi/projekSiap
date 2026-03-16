<?php

namespace App\Filament\Resources\Kkms\Pages;

use App\Filament\Resources\Kkms\KkmResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewKkm extends ViewRecord
{
    protected static string $resource = KkmResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->label('Edit'),
            Actions\Action::make('back')
                ->label('Kembali')
                ->url(KkmResource::getUrl('index'))
                ->color('gray'),
        ];
    }
}
