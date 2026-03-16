<?php

namespace App\Filament\Resources\JamPelajarans;

use App\Filament\Resources\JamPelajarans\Pages;
use App\Filament\Resources\JamPelajarans\Schemas\JamPelajaranForm;
use App\Filament\Resources\JamPelajarans\Tables\JamPelajaransTable;
use App\Models\JamPelajaran;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Schemas\Schema;

class JamPelajaranResource extends Resource
{
    protected static ?string $model = JamPelajaran::class;

    public static function getNavigationLabel(): string
    {
        return 'Jam Pelajaran';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Akademik';
    }

    public static function getNavigationSort(): ?int
    {
        return 3;
    }

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-clock';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema(JamPelajaranForm::getSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(JamPelajaransTable::getColumns())
            ->filters(JamPelajaransTable::getFilters())
            ->actions(JamPelajaransTable::getActions())
            ->bulkActions(JamPelajaransTable::getBulkActions());
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJamPelajarans::route('/'),
            'create' => Pages\CreateJamPelajaran::route('/create'),
            'view' => Pages\ViewJamPelajaran::route('/{record}'),
            'edit' => Pages\EditJamPelajaran::route('/{record}/edit'),
        ];
    }
}
