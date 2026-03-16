<?php

namespace App\Filament\Resources\Kelas;

use App\Filament\Resources\Kelas\Pages;
use App\Filament\Resources\Kelas\Schemas\KelasForm;
use App\Filament\Resources\Kelas\Tables\KelasTable;
use App\Filament\Resources\Kelas\RelationManagers\SantrisRelationManager;
use App\Models\Kelas;
use App\Models\User;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class KelasResource extends Resource
{
    protected static ?string $model = Kelas::class;
    protected static ?string $recordTitleAttribute = 'nama_kelas';

    public static function canViewAny(): bool
    {
        return Gate::allows('ViewAny:KelasResource');
    }

    public static function canView($record): bool
    {
        return Gate::allows('View:KelasResource');
    }

    public static function canCreate(): bool
    {
        return Gate::allows('Create:KelasResource');
    }

    public static function canEdit($record): bool
    {
        return Gate::allows('Update:KelasResource');
    }

    public static function canDelete($record): bool
    {
        return Gate::allows('Delete:KelasResource');
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
        return 2;
    }

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-academic-cap';
    }

    // ✅ TAMBAH: Eager Loading untuk optimasi query + Filter untuk Ustadz
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery()
            ->with(['jurusan', 'waliKelas']);

        /** @var User|null $user */
        $user = Auth::user();

        // Jika user adalah ustadz, filter kelas yang dia ampu
        if ($user && $user->hasRole('ustadz') && $user->ustadz_id) {
            $ustadzId = $user->ustadz_id;

            // Tampilkan kelas dimana ustadz:
            // 1. Menjadi wali kelas, ATAU
            // 2. Mengajar di kelas tersebut (via jadwal_pelajarans)
            $query->where(function ($q) use ($ustadzId) {
                $q->where('wali_kelas_id', $ustadzId)
                  ->orWhereHas('jadwalPelajarans', function ($jq) use ($ustadzId) {
                      $jq->where('ustadz_id', $ustadzId);
                  });
            });
        }

        return $query;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->schema(KelasForm::getSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(KelasTable::getColumns())
            ->filters(KelasTable::getFilters())
            ->actions(KelasTable::getActions())
            ->bulkActions(KelasTable::getBulkActions());
    }

    // ✅ ATUR RELATIONS: HANYA DI VIEW PAGE (SAMA SEPERTI SANTRI)
    public static function getRelations(): array
    {
        // Jika di Edit page, return array kosong
        if (static::isEditPage()) {
            return [];
        }
        
        // Jika di View page, tampilkan Relation Manager
        return [
            SantrisRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKelas::route('/'),
            'create' => Pages\CreateKelas::route('/create'),
            'view' => Pages\ViewKelas::route('/{record}'),
            'edit' => Pages\EditKelas::route('/{record}/edit'),
        ];
    }

    // ✅ METHOD UNTUK DETEKSI EDIT PAGE (SAMA SEPERTI SANTRI)
    private static function isEditPage(): bool
    {
        $currentUrl = url()->current();
        return str_contains($currentUrl, '/edit');
    }
}