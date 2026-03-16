<?php

namespace App\Filament\Resources\Kelulusans;

use App\Filament\Resources\Kelulusans\Pages;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class KelulusanResource extends Resource
{
    protected static ?string $model = Kelas::class;

    protected static ?string $slug = 'laporan-kelulusan';

    public static function canViewAny(): bool
    {
        return Gate::allows('ViewAny:Kelulusan');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Gate::allows('ViewAny:Kelulusan');
    }

    public static function canView($record): bool
    {
        return Gate::allows('View:Kelulusan');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return Gate::allows('Update:Kelulusan');
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function getNavigationLabel(): string
    {
        return 'Kelulusan';
    }

    public static function getModelLabel(): string
    {
        return 'Kelulusan';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Kelulusan';
    }

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-academic-cap';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Laporan';
    }

    public static function getNavigationSort(): ?int
    {
        return 6;
    }

    /**
     * Kelas akhir yang bisa lulus (kelas 12, 9, 6)
     */
    public static function getKelasAkhirPatterns(): array
    {
        return ['12%', '9%', '6%'];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with(['waliKelas']);

        // Filter hanya kelas akhir (12, 9, 6)
        $query->where(function ($q) {
            foreach (static::getKelasAkhirPatterns() as $pattern) {
                $q->orWhere('nama_kelas', 'LIKE', $pattern);
            }
        });

        /** @var User|null $user */
        $user = Auth::user();

        // Jika ustadz, hanya tampilkan kelas yang dia walikan (wali kelas saja)
        if ($user && $user->hasRole('ustadz') && $user->ustadz_id) {
            $query->where('wali_kelas_id', $user->ustadz_id);
        }

        return $query;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no')
                    ->label('NO')
                    ->rowIndex()
                    ->sortable(false),

                TextColumn::make('nama_kelas')
                    ->label('Kelas')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('tahun_ajaran_display')
                    ->label('Tahun Ajaran')
                    ->state(function ($record, $livewire) {
                        $tahunAjaranId = $livewire->tableFilters['tahun_ajaran']['value'] ?? null;
                        if ($tahunAjaranId) {
                            $tahunAjaran = TahunAjaran::find($tahunAjaranId);
                            return $tahunAjaran?->tahun_ajaran ?? '-';
                        }
                        $activeTahunAjaran = TahunAjaran::where('status', true)->first();
                        return $activeTahunAjaran?->tahun_ajaran ?? '-';
                    }),
            ])
            ->filters([
                SelectFilter::make('nama_kelas')
                    ->label('Kelas')
                    ->options(function () {
                        /** @var User|null $user */
                        $user = Auth::user();
                        $query = Kelas::orderBy('nama_kelas');

                        // Filter hanya kelas akhir (12, 9, 6)
                        $query->where(function ($q) {
                            foreach (KelulusanResource::getKelasAkhirPatterns() as $pattern) {
                                $q->orWhere('nama_kelas', 'LIKE', $pattern);
                            }
                        });

                        // Jika ustadz, hanya tampilkan kelas yang dia walikan
                        if ($user && $user->hasRole('ustadz') && $user->ustadz_id) {
                            $query->where('wali_kelas_id', $user->ustadz_id);
                        }

                        return $query->pluck('nama_kelas', 'nama_kelas');
                    })
                    ->searchable()
                    ->preload()
                    ->query(function (Builder $query, array $data) {
                        if ($data['value']) {
                            $query->where('nama_kelas', $data['value']);
                        }
                    }),

                SelectFilter::make('tahun_ajaran')
                    ->label('Tahun Ajaran')
                    ->options(function () {
                        return TahunAjaran::orderByDesc('tahun_awal')
                            ->get()
                            ->pluck('tahun_ajaran', 'id');
                    })
                    ->default(fn () => TahunAjaran::where('status', true)->first()?->id)
                    ->query(fn (Builder $query) => $query)
                    ->indicateUsing(fn () => null),
            ])
            ->actions([
                Actions\Action::make('lihat_kelulusan')
                    ->label('Lihat List Kelulusan')
                    ->icon('heroicon-o-eye')
                    ->color('primary')
                    ->url(function (Kelas $record, $livewire) {
                        $tahunAjaranId = $livewire->tableFilters['tahun_ajaran']['value'] ?? TahunAjaran::where('status', true)->first()?->id;

                        return static::getUrl('list-santri', [
                            'record' => $record->id,
                            'tahun_ajaran' => $tahunAjaranId,
                        ]);
                    }),
            ])
            ->defaultSort('nama_kelas', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKelulusans::route('/'),
            'list-santri' => Pages\ListKelulusanSantris::route('/{record}/tahun-ajaran/{tahun_ajaran}'),
            'view-santri' => Pages\ViewKelulusanSantri::route('/{record}/santri/{santri}/tahun-ajaran/{tahun_ajaran}/view'),
            'edit-santri' => Pages\EditKelulusanSantri::route('/{record}/santri/{santri}/tahun-ajaran/{tahun_ajaran}/edit'),
        ];
    }
}
