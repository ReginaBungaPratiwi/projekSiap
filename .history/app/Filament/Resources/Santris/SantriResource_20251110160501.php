<?php

namespace App\Filament\Resources\Santris;

use App\Filament\Resources\Santris\Pages;
use App\Filament\Resources\Santris\Schemas\SantriForm;
use App\Filament\Resources\Santris\Tables\SantrisTable;
use App\Models\Santri;
use Filament\Resources\Resource;

class SantriResource extends Resource
{
    protected static ?string $model = Santri::class;

    // ✅ HAPUS SEMUA PROPERTY - BIARKAN DEFAULT
    // protected static ?string $navigationLabel = 'Data Santri';
    // protected static ?string $modelLabel = 'Santri';
    // protected static ?string $pluralModelLabel = 'Data Santri';
    // protected static ?string $navigationGroup = 'Data Master';
    // protected static ?int $navigationSort = 1;

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
        return [
            //
        ];
    }

    public static function getTableActions(): array
    {
        return [
            \Filament\Tables\Actions\ViewAction::make(),
            \Filament\Tables\Actions\EditAction::make(),
        ];
    }

    public static function getTableBulkActions(): array
    {
        return [
            \Filament\Tables\Actions\BulkActionGroup::make([
                \Filament\Tables\Actions\DeleteBulkAction::make(),
            ]),
        ];
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
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
}