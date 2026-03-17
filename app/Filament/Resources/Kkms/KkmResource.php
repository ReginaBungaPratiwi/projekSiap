<?php

namespace App\Filament\Resources\Kkms;

use App\Filament\Resources\Kkms\Pages;
use App\Filament\Resources\Kkms\Schemas\KkmForm;
use App\Filament\Resources\Kkms\Tables\KkmsTable;
use App\Models\Kelas;
use App\Models\Kkm;
use App\Models\User;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class KkmResource extends Resource
{
    protected static ?string $model = Kkm::class;

    public static function getNavigationLabel(): string
    {
        return 'Input KKM';
    }

    public static function getModelLabel(): string
    {
        return 'KKM';
    }

    public static function getPluralModelLabel(): string
    {
        return 'KKM';
    }

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-clipboard-document-check';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Penilaian Santri';
    }

    public static function getNavigationSort(): ?int
    {
        return 2;
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->with(['mapel', 'kelas', 'tahunAjaran', 'semester', 'ustadz']);

        /** @var User|null $user */
        $user = Auth::user();

        // Jika user adalah ustadz, filter KKM dari kelas yang dia ampu
        if ($user && $user->hasRole('ustadz') && $user->ustadz_id) {
            $ustadzId = $user->ustadz_id;

            // Dapatkan ID kelas yang ustadz ampu (wali kelas atau mengajar)
            $kelasIds = Kelas::where('wali_kelas_id', $ustadzId)
                ->orWhereHas('jadwalPelajarans', function ($jq) use ($ustadzId) {
                    $jq->where('ustadz_id', $ustadzId);
                })
                ->pluck('id')
                ->toArray();

            $query->whereIn('kelas_id', $kelasIds)
                ->where('ustadz_id', $ustadzId);
        }

        return $query;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema(KkmForm::getSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(KkmsTable::getColumns())
            ->filters(KkmsTable::getFilters())
            ->actions(KkmsTable::getActions())
            ->bulkActions(KkmsTable::getBulkActions())
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKkms::route('/'),
            'create' => Pages\CreateKkm::route('/create'),
            'view' => Pages\ViewKkm::route('/{record}'),
            'edit' => Pages\EditKkm::route('/{record}/edit'),
        ];
    }
}
