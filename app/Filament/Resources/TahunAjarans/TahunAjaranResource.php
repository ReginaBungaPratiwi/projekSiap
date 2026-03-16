<?php

namespace App\Filament\Resources\TahunAjarans;

use App\Filament\Resources\TahunAjarans\Pages;
use App\Filament\Resources\TahunAjarans\Schemas\TahunAjaranForm;
use App\Filament\Resources\TahunAjarans\Tables\TahunAjaransTable;
use App\Models\TahunAjaran;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Schemas\Schema; // ✅ IMPORT YANG BENAR

class TahunAjaranResource extends Resource
{
    protected static ?string $model = TahunAjaran::class;

    public static function getNavigationLabel(): string
    {
        return 'Tahun Ajaran';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Akademik';
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-calendar';
    }

    // ✅ GUNAKAN Schema BUKAN Form
    public static function form(Schema $schema): Schema
    {
        return $schema->schema(TahunAjaranForm::getSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(TahunAjaransTable::getColumns())
            ->filters(TahunAjaransTable::getFilters())
            ->actions(TahunAjaransTable::getActions())
            ->bulkActions(TahunAjaransTable::getBulkActions());
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTahunAjarans::route('/'),
            'create' => Pages\CreateTahunAjaran::route('/create'),
            'view' => Pages\ViewTahunAjaran::route('/{record}'),
            'edit' => Pages\EditTahunAjaran::route('/{record}/edit'),
        ];
    }
}