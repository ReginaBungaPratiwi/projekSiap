<?php

namespace App\Filament\Resources\Jurusans\Tables;

use App\Models\Jurusan;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions;
use Illuminate\Database\Eloquent\Builder;

class JurusansTable
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns(self::getColumns())
            ->filters(self::getFilters())
            ->actions(self::getActions())
            ->bulkActions(self::getBulkActions())
            ->defaultSort('created_at', 'desc'); // ✅ DEFAULT SORTING
    }

    public static function getColumns(): array
    {
        return [
            TextColumn::make('no')
                ->label('No')
                ->rowIndex()
                ->sortable(false), // ❌ NON-SORTABLE

            TextColumn::make('nama_jurusan')
                ->label('Nama Jurusan')
                ->sortable() // ✅ SORTABLE
                ->searchable(),

            TextColumn::make('deskripsi')
                ->label('Deskripsi')
                ->limit(50)
                ->searchable()
                ->wrap()
                ->placeholder('-')
                ->sortable(false), // ❌ NON-SORTABLE

            IconColumn::make('status')
                ->label('Status')
                ->boolean()
                ->trueIcon('heroicon-o-check-badge')
                ->falseIcon('heroicon-o-x-mark')
                ->trueColor('success')
                ->falseColor('danger')
                ->sortable(), // ✅ SORTABLE

            TextColumn::make('created_at')
                ->label('Dibuat Pada')
                ->dateTime('d/m/Y H:i')
                ->sortable() // ✅ SORTABLE
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    public static function getFilters(): array
    {
        return [
            SelectFilter::make('status')
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
            Actions\ViewAction::make()->label('View'),
            Actions\EditAction::make()->label('Edit'),
            
            Actions\Action::make('activate')
                ->label('Aktifkan')
                ->icon('heroicon-o-check')
                ->color('success')
                ->action(function ($record) {
                    $record->update(['status' => true]);
                })
                ->hidden(fn ($record) => $record->status)
                ->requiresConfirmation()
                ->modalHeading('Aktifkan')
                ->modalDescription('Are you sure you would like to do this?'),

            Actions\Action::make('deactivate')
                ->label('Nonaktifkan')
                ->icon('heroicon-o-x-mark')
                ->color('danger')
                ->action(function ($record) {
                    $record->update(['status' => false]);
                })
                ->visible(fn ($record) => $record->status)
                ->requiresConfirmation()
                ->modalHeading('Nonaktifkan')
                ->modalDescription('Are you sure you would like to do this?'),

            Actions\DeleteAction::make()->label('Hapus'),
        ];
    }

    public static function getBulkActions(): array
    {
        return [
            Actions\BulkActionGroup::make([
                Actions\DeleteBulkAction::make()->label('Hapus yang dipilih'),
                
                Actions\BulkAction::make('activateSelected')
                    ->label('Aktifkan yang dipilih')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Aktifkan')
                    ->modalDescription('Are you sure you would like to do this?')
                    ->action(function ($records) {
                        $records->each->update(['status' => true]);
                    }),

                Actions\BulkAction::make('deactivateSelected')
                    ->label('Nonaktifkan yang dipilih')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Nonaktifkan')
                    ->modalDescription('Are you sure you would like to do this?')
                    ->action(function ($records) {
                        $records->each->update(['status' => false]);
                    }),
            ]),
        ];
    }
}