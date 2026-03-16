<?php

namespace App\Filament\Resources\Santris\Pages;

use App\Filament\Resources\Santris\SantriResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords; // ✅ IMPORT YANG BENAR

class ListSantris extends ListRecords
{
    protected static string $resource = SantriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}