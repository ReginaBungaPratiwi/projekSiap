<?php

namespace App\Filament\Resources\SantriResource\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ActionsColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;

class SantrisTable
{
    public static function getColumns(): array
    {
        return [
            TextColumn::make('id')
                ->label('No')
                ->rowIndex()
                ->sortable(),
                
            TextColumn::make('nama_santri')
                ->label('Nama Santri')
                ->searchable()
                ->sortable(),
                
            TextColumn::make('nis')
                ->label('NIS')
                ->searchable()
                ->sortable(),
                
            TextColumn::make('kelas')
                ->searchable()
                ->sortable(),
                
            TextColumn::make('jenjang')
                ->searchable()
                ->sortable(),
                
            TextColumn::make('tahun_masuk')
                ->sortable(),
                
            ActionsColumn::make('actions')
                ->label('Aksi')
                ->actions([
                    ActionGroup::make([
                        ViewAction::make(),
                        EditAction::make(),
                        DeleteAction::make(),
                    ])
                ]),
        ];
    }

    public static function getActions(): array
    {
        return [
            // Actions sudah di handle di column Aksi
        ];
    }

    public static function getBulkActions(): array
    {
        return [
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ];
    }
}