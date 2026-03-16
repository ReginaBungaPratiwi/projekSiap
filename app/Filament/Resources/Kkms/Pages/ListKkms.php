<?php

namespace App\Filament\Resources\Kkms\Pages;

use App\Filament\Resources\Kkms\KkmResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKkms extends ListRecords
{
    protected static string $resource = KkmResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('New KKM'),
        ];
    }
}
