<?php

namespace App\Filament\Resources\Santris;

use App\Filament\Resources\Santris\Pages;
use App\Models\Santri;
use Filament\Resources\Resource;
use Filament\Forms;
use Filament\Tables;

class SantriResource extends Resource
{
    protected static ?string $model = Santri::class;

    // TIDAK ADA PROPERTY LAIN SELAIN MODEL

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

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-users';
    }

    // ✅ METHOD FORM YANG BENAR UNTUK FILAMENT v4
    public static function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
        ];
    }

    // ✅ METHOD TABLE YANG BENAR UNTUK FILAMENT v4
    public static function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name'),
        ];
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