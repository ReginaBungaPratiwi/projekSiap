<?php

namespace App\Filament\Resources\Santris;

use App\Filament\Resources\Santris\Pages;
use App\Filament\Resources\Santris\RelationManagers\RiwayatKelasRelationManager;
use App\Filament\Resources\Santris\Tables\SantrisTable;
use App\Filament\Resources\Santris\Schemas\SantriForm;
use App\Models\Kelas;
use App\Models\Santri;
use App\Models\User;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SantriResource extends Resource
{
    protected static ?string $model = Santri::class;
    protected static ?string $recordTitleAttribute = 'nama_lengkap';

    public static function getNavigationGroup(): ?string
    {
        /** @var User|null $user */
        $user = Auth::user();
        if ($user && $user->hasRole('ustadz')) {
            return 'Data Santri';
        }
        return 'Data Master';
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-user';
    }

    // ✅ Filter santri berdasarkan kelas yang ustadz ampu
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        /** @var User|null $user */
        $user = Auth::user();

        // Jika user adalah ustadz, filter santri dari kelas yang dia ampu
        if ($user && $user->hasRole('ustadz') && $user->ustadz_id) {
            $ustadzId = $user->ustadz_id;

            // Dapatkan ID kelas yang ustadz ampu (wali kelas atau mengajar)
            $kelasIds = Kelas::where('wali_kelas_id', $ustadzId)
                ->orWhereHas('jadwalPelajarans', function ($jq) use ($ustadzId) {
                    $jq->where('ustadz_id', $ustadzId);
                })
                ->pluck('id')
                ->toArray();

            // Filter santri yang kelasnya ada di daftar kelas ustadz
            $query->whereIn('kelas_id', $kelasIds);
        }

        return $query;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema(
            SantriForm::schema()
        );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(SantrisTable::getColumns())
            ->filters(SantrisTable::getFilters())
            ->actions(SantrisTable::getActions())
            ->bulkActions(SantrisTable::getBulkActions());
    }

    // ✅ ATUR RELATIONS: HANYA DI VIEW PAGE
    public static function getRelations(): array
    {
        return [
            RiwayatKelasRelationManager::class,
        ];
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

    public static function create(array $data): Model
    {
        return Santri::createWithRiwayat($data);
    }

    public static function update(Model $record, array $data): Model
    {
        if (isset($data['kelas_id']) && $data['kelas_id'] != $record->kelas_id) {
            $record->pindahKelas($data['kelas_id']);
            unset($data['kelas_id']);
        }

        if (!empty($data)) {
            $record->update($data);
        }

        return $record;
    }
}