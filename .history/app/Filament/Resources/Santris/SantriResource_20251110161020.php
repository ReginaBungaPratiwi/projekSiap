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

    // ✅ TAMBAHKAN NAVIGATION ICON
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    // ✅ GUNAKAN METHOD UNTUK LABEL
    public static function getNavigationLabel(): string
    {
        return 'Santris';
    }

    public static function getModelLabel(): string
    {
        return 'Santri';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Santris';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Data Master';
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

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