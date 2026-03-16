<?php

namespace App\Filament\Resources\Mapels;

use App\Filament\Resources\Mapels\Pages;
use App\Filament\Resources\Mapels\Schemas\MapelForm;
use App\Filament\Resources\Mapels\Tables\MapelsTable;
use App\Models\Mapel;
use App\Models\User;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class MapelResource extends Resource
{
    protected static ?string $model = Mapel::class;

    public static function getNavigationLabel(): string
    {
        return 'Mata Pelajaran';
    }

    public static function getNavigationGroup(): ?string
    {
        /** @var User|null $user */
        $user = Auth::user();
        if ($user && $user->hasRole('ustadz')) {
            return 'Data Ustadz';
        }
        return 'Data Master';
    }

    public static function getNavigationSort(): ?int
    {
        return 5;
    }

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-book-open';
    }

    // ✅ Filter mapel berdasarkan ustadz yang login
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with(['jurusan', 'pengampu']);

        /** @var User|null $user */
        $user = Auth::user();

        // Jika user adalah ustadz, filter mapel yang dia ampu
        if ($user && $user->hasRole('ustadz') && $user->ustadz_id) {
            $query->whereHas('pengampu', function ($q) use ($user) {
                $q->where('ustadzs.id', $user->ustadz_id);
            });
        }

        return $query;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema(MapelForm::getSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(MapelsTable::getColumns())
            ->filters(MapelsTable::getFilters())
            ->actions(MapelsTable::getActions())
            ->bulkActions(MapelsTable::getBulkActions());
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMapels::route('/'),
            'create' => Pages\CreateMapel::route('/create'),
            'view' => Pages\ViewMapel::route('/{record}'),
            'edit' => Pages\EditMapel::route('/{record}/edit'),
        ];
    }
}