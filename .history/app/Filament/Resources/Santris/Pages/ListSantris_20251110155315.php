<?php

namespace App\Filament\Resources\Santris\Pages; // ✅ PERBAIKI NAMESPACE

use App\Filament\Resources\Santris\SantriResource; // ✅ PERBAIKI IMPORT
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

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