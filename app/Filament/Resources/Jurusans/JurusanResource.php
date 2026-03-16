<?php
// app/Filament/Resources/Jurusans/JurusanResource.php

namespace App\Filament\Resources\Jurusans;

use App\Filament\Resources\Jurusans\Pages;
use App\Filament\Resources\Jurusans\Tables\JurusansTable;
use App\Models\Jurusan;
use App\Models\User;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class JurusanResource extends Resource
{
    protected static ?string $model = Jurusan::class;

    protected static ?string $navigationLabel = 'Jurusan';

    public static function getNavigationGroup(): string
    {
        /** @var User|null $user */
        $user = Auth::user();
        if ($user && $user->hasRole('ustadz')) {
            return 'Data Ustadz';
        }
        return 'Data Master';
    }

    public static function getNavigationSort(): int
    {
        return 4;
    }

    public static function getNavigationIcon(): string
{
    return 'heroicon-o-building-library';
}

    protected static ?string $recordTitleAttribute = 'nama_jurusan';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema(\App\Filament\Resources\Jurusans\Schemas\JurusanForm::schema())
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return JurusansTable::table($table);
    }

    public static function getRelations(): array
    {
        return [
            // ✅ KOSONGKAN ATAU HAPUS RELATIONS
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJurusans::route('/'),
            'create' => Pages\CreateJurusan::route('/create'),
            'view' => Pages\ViewJurusan::route('/{record}'),
            'edit' => Pages\EditJurusan::route('/{record}/edit'),
        ];
    }
}