<?php

namespace App\Filament\Resources\SantriResource;

use App\Filament\Resources\SantriResource\Schemas\SantriForm;
use App\Filament\Resources\SantriResource\Tables\SantrisTable;
use App\Filament\Resources\SantriResource\Pages;
use App\Models\Santri;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;

class SantriResource extends Resource
{
    protected static ?string $model = Santri::class;
    protected static ?string $recordTitleAttribute = 'nama_santri';

    public static function form(Form $form): Form
    {
        return $form->schema(SantriForm::getSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(SantrisTable::getColumns())
            ->actions(SantrisTable::getActions())
            ->bulkActions(SantrisTable::getBulkActions());
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

    public static function getNavigationSort(): ?int
    {
        return 3;
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Data Master';
    }

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-user-group';
    }

    public static function getNavigationLabel(): string
    {
        return 'Santri';
    }
}