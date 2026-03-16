<?php

namespace App\Filament\Resources\Semesters;

use App\Filament\Resources\Semesters\Pages;
use App\Filament\Resources\Semesters\Schemas\SemesterForm;
use App\Filament\Resources\Semesters\Tables\SemestersTable;
use App\Models\Semester;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;

class SemesterResource extends Resource
{
    protected static ?string $model = Semester::class;

    public static function getNavigationLabel(): string
    {
        return 'Semester';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Akademik';
    }

    public static function getNavigationSort(): ?int
    {
        return 2;
    }

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-calendar-days';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['tahunAjaran']);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema(SemesterForm::getSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(SemestersTable::getColumns())
            ->filters(SemestersTable::getFilters())
            ->actions(SemestersTable::getActions())
            ->bulkActions(SemestersTable::getBulkActions())
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSemesters::route('/'),
            'create' => Pages\CreateSemester::route('/create'),
            'view' => Pages\ViewSemester::route('/{record}'),
            'edit' => Pages\EditSemester::route('/{record}/edit'),
        ];
    }
}
