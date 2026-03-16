<?php

namespace App\Filament\Resources\Ustadzs\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\BulkAction;
use Illuminate\Database\Eloquent\Model;

class UstadzsTable
{
    public static function getColumns(): array
    {
        return [
            TextColumn::make('no')
                ->label('No')
                ->rowIndex()
                ->sortable(false),

            TextColumn::make('nama')
                ->label('Nama')
                ->searchable()
                ->sortable(),

            TextColumn::make('nip')
                ->label('NIP')
                ->searchable()
                ->sortable(),

            // ✅ PERBAIKAN: Cara paling sederhana
            TextColumn::make('mataPelajarans')
                ->label('Mata Pelajaran')
                ->formatStateUsing(function ($state, $record) {
                    return $record->mataPelajarans->count() > 0 
                        ? $record->mataPelajarans->pluck('nama_mapel')->join(', ')
                        : 'Belum di atur';
                })
                ->badge()
                ->color('gray')
                ->sortable(false),

            IconColumn::make('status_aktif')
                ->label('Status')
                ->boolean()
                ->trueIcon('heroicon-o-check-badge')
                ->falseIcon('heroicon-o-x-mark')
                ->trueColor('success')
                ->falseColor('danger')
                ->sortable(),
        ];
    }

    public static function getFilters(): array
    {
        return [
            SelectFilter::make('status_aktif')
                ->label('Status')
                ->options([
                    true => 'Aktif',
                    false => 'Tidak Aktif',
                ])
                ->placeholder('Semua Status'),
        ];
    }

    public static function getActions(): array
    {
        return [
            ViewAction::make()->label('View'),
            EditAction::make()->label('Edit'),
            
            Action::make('activate')
                ->label('Aktifkan')
                ->icon('heroicon-o-check')
                ->color('success')
                ->action(function (Model $record) {
                    $record->update(['status_aktif' => true]);
                })
                ->hidden(fn (Model $record) => $record->status_aktif)
                ->requiresConfirmation()
                ->modalHeading('Aktifkan')
                ->modalDescription('Are you sure you would like to do this?'),

            Action::make('deactivate')
                ->label('Nonaktifkan')
                ->icon('heroicon-o-x-mark')
                ->color('danger')
                ->action(function (Model $record) {
                    $record->update(['status_aktif' => false]);
                })
                ->visible(fn (Model $record) => $record->status_aktif)
                ->requiresConfirmation()
                ->modalHeading('Nonaktifkan')
                ->modalDescription('Are you sure you would like to do this?'),

            DeleteAction::make()->label('Hapus'),
        ];
    }

    public static function getBulkActions(): array
    {
        return [
            BulkActionGroup::make([
                DeleteBulkAction::make()->label('Hapus yang dipilih'),
                
                BulkAction::make('activateSelected')
                    ->label('Aktifkan yang dipilih')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Aktifkan')
                    ->modalDescription('Are you sure you would like to do this?')
                    ->action(function ($records) {
                        $records->each->update(['status_aktif' => true]);
                    }),

                BulkAction::make('deactivateSelected')
                    ->label('Nonaktifkan yang dipilih')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Nonaktifkan')
                    ->modalDescription('Are you sure you would like to do this?')
                    ->action(function ($records) {
                        $records->each->update(['status_aktif' => false]);
                    }),
            ]),
        ];
    }
}