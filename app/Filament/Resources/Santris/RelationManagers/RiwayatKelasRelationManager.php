<?php

namespace App\Filament\Resources\Santris\RelationManagers;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class RiwayatKelasRelationManager extends RelationManager
{
    protected static string $relationship = 'riwayatKelas';

    protected static ?string $title = 'Riwayat Kelas';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $pageClass === \App\Filament\Resources\Santris\Pages\ViewSantri::class;
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('tahun_akademik')
            ->modifyQueryUsing(fn (Builder $query) => $query->orderBy('tahun_akademik', 'desc'))
            ->columns([
                Tables\Columns\TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tahun_akademik')
                    ->label('Tahun Akademik')
                    ->searchable(),
            ])
            ->headerActions([])
            ->actions([])
            ->bulkActions([])
            ->emptyStateHeading('Belum ada riwayat kelas')
            ->emptyStateDescription('Riwayat kelas akan muncul di sini ketika santri dinaikkan kelasnya.')
            ->emptyStateIcon('heroicon-o-academic-cap');
    }
}