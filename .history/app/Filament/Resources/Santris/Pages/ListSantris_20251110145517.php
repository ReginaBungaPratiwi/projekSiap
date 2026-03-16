<?php

namespace App\Filament\Resources\SantriResource\Pages;

use App\Filament\Resources\SantriResource;
use App\Filament\Resources\SantriResource\Tables\SantrisTable;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSantris extends ListRecords
{
    protected static string $resource = SantriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Santri')
                ->icon('heroicon-o-plus'),
        ];
    }

    protected function getTable(): string
    {
        return SantrisTable::class;
    }
}