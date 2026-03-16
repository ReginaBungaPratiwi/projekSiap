<?php

namespace App\Filament\Resources\JadwalPelajarans;

use App\Filament\Resources\JadwalPelajarans\Pages;
use App\Filament\Resources\JadwalPelajarans\Schemas\JadwalPelajaranForm;
use App\Filament\Resources\JadwalPelajarans\Tables\JadwalPelajaransTable;
use App\Models\JadwalPelajaran;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class JadwalPelajaranResource extends Resource
{
    protected static ?string $model = JadwalPelajaran::class;

    public static function getNavigationLabel(): string
    {
        return 'Jadwal Pelajaran';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Akademik';
    }

    public static function getNavigationSort(): ?int
    {
        return 4;
    }

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-clipboard-document-list';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['tahunAjaran', 'semester', 'kelas', 'jamPelajaran', 'mapel', 'ustadz']);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema(JadwalPelajaranForm::getSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(JadwalPelajaransTable::getColumns())
            ->filters(JadwalPelajaransTable::getFilters())
            ->actions(JadwalPelajaransTable::getActions())
            ->bulkActions(JadwalPelajaransTable::getBulkActions())
            ->defaultSort('jam_pelajaran_id', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJadwalPelajarans::route('/'),
            'create' => Pages\CreateJadwalPelajaran::route('/create'),
            'view' => Pages\ViewJadwalPelajaran::route('/{record}'),
            'edit' => Pages\EditJadwalPelajaran::route('/{record}/edit'),
        ];
    }
}
