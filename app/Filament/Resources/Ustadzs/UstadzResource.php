<?php

namespace App\Filament\Resources\Ustadzs;

use App\Filament\Resources\Ustadzs\Pages\CreateUstadz;
use App\Filament\Resources\Ustadzs\Pages\EditUstadz;
use App\Filament\Resources\Ustadzs\Pages\ListUstadzs;
use App\Filament\Resources\Ustadzs\Pages\ViewUstadz;
use App\Filament\Resources\Ustadzs\Schemas\UstadzForm;
use App\Filament\Resources\Ustadzs\Tables\UstadzsTable;
use App\Models\Ustadz;
use App\Models\User;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class UstadzResource extends Resource
{
    protected static ?string $model = Ustadz::class;

    protected static ?string $recordTitleAttribute = 'nama';

    protected static ?string $navigationLabel = 'Ustadz/Ustadzah';

    protected static ?string $pluralModelLabel = 'Ustadz/Ustadzah';

    protected static ?string $modelLabel = 'Ustadz/Ustadzah';

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
        return 2;
    }

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-pencil-square';
    }

    /**
     * ✅ TAMBAHKAN METHOD INI: Eager Loading untuk optimasi query
     */
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->with(['mataPelajarans']);
    }

    /**
     * FORM - Sama seperti SantriResource
     */
    public static function form(Schema $schema): Schema
    {
        return $schema->schema(
            UstadzForm::schema()
        );
    }

    /**
     * TABLE
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns(UstadzsTable::getColumns())
            ->filters(UstadzsTable::getFilters())
            ->actions(UstadzsTable::getActions())
            ->bulkActions(UstadzsTable::getBulkActions());
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUstadzs::route('/'),
            'create' => CreateUstadz::route('/create'),
            'view' => ViewUstadz::route('/{record}'),
            'edit' => EditUstadz::route('/{record}/edit'),
        ];
    }
}