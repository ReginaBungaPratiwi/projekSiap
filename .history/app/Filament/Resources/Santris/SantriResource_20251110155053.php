<?php

namespace App\Filament\Resources\Santris;

use App\Filament\Resources\Santris\Pages;
use App\Filament\Resources\Santris\Schemas\SantriForm;
use App\Filament\Resources\Santris\Tables\SantrisTable;
use App\Models\Santri;
use Filament\Resources\Resource;
use Filament\Forms;
use Filament\Tables;

class SantriResource extends Resource
{
    protected static ?string $model = Santri::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    // ✅ DI FILAMENT V4, GUNAKAN getFormSchema() DAN getTableColumns()
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
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
        ];
    }

    public static function getTableBulkActions(): array
    {
        return [
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
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