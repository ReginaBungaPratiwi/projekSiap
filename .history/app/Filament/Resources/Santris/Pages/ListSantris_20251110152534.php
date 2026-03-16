<?php

namespace App\Filament\Resources\SantriResource\Pages;

use App\Filament\Resources\SantriResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSantris extends ListRecords
{
    protected static string $resource = SantriResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Santri'),
        ];
    }

    protected function getTableColumns(): array
    {
        return $this->getResource()::getTableColumns();
    }

    protected function getTableFilters(): array
    {
        return $this->getResource()::getTableFilters();
    }

    protected function getTableActions(): array
    {
        return $this->getResource()::getTableActions();
    }

    protected function getTableBulkActions(): array
    {
        return $this->getResource()::getTableBulkActions();
    }
}