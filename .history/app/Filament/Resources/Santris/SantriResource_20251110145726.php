<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SantriResource\Pages;
use App\Filament\Resources\SantriResource\Tables\SantrisTable;
use App\Models\Santri;
use Filament\Resources\Resource;

class SantriResource extends Resource
{
    protected static ?string $model = Santri::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Santri';
    protected static ?string $navigationGroup = 'Data Master';
    protected static ?string $slug = 'santri';

    // UBAH MENJADI METHOD STATIC
    public static function table($table)
    {
        return new SantrisTable;
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