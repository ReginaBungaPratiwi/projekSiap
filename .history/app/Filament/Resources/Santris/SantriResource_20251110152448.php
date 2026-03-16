<?php

namespace App\Filament\Resources\SantriResource;

use App\Filament\Resources\SantriResource\Pages;
use App\Filament\Resources\SantriResource\Schemas\SantriForm;
use App\Filament\Resources\SantriResource\Tables\SantrisTable;
use App\Models\Santri;
use Filament\Resources\Resource;

class SantriResource extends Resource
{
    protected static ?string $model = Santri::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Santri';
    protected static ?string $slug = 'santri';

    public static function getFormSchema(): array
    {
        return SantriForm::getSchema();
    }

    public static function getTableColumns(): array
    {
        return SantrisTable::getColumns();
    }

    public static function getTableFilters(): array
    {
        return SantrisTable::getFilters();
    }

    public static function getTableActions(): array
    {
        return SantrisTable::getActions();
    }

    public static function getTableBulkActions(): array
    {
        return SantrisTable::getBulkActions();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSantris::route('/'),
            'create' => Pages\CreateSantri::route('/create'),
            'view' => Pages\ViewSantri::route('/{record}'),
            'edit' => Pages\EditSantri::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Data Master';
    }
}