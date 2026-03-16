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

    // ✅ GUNAKAN PROPERTY YANG AMAN
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        return $form->schema(SantriForm::getSchema());
    }

    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return SantrisTable::make($table);
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

    // ✅ METHOD YANG AMAN
    public static function getNavigationLabel(): string
    {
        return 'Santri';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Data Master';
    }
}