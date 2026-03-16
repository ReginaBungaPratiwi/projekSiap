<?php

namespace App\Filament\Resources\Santris;

use App\Filament\Resources\Santris\Pages;
use App\Filament\Resources\Santris\Schemas\SantriForm;
use App\Filament\Resources\Santris\Tables\SantrisTable;
use App\Models\Santri;
use Filament\Resources\Resource;

class SantriResource extends Resource
{
    protected static ?string $model = Santri::class;

    // ✅ SEMENTARA HAPUS SEMUA PROPERTY YANG BERMASALAH
    // protected static ?string $navigationIcon = 'heroicon-o-user-group';
    // protected static ?string $navigationLabel = 'Santri';
    // protected static ?string $modelLabel = 'Santri';
    // protected static ?string $pluralModelLabel = 'Santri';
    // protected static ?string $navigationGroup = 'Data';

    public static function form(\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        return $form->schema(SantriForm::getSchema());
    }

    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return $table
            ->columns(SantrisTable::getColumns())
            ->filters([
                //
            ])
            ->actions([
                \Filament\Tables\Actions\ViewAction::make(),
                \Filament\Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                \Filament\Tables\Actions\BulkActionGroup::make([
                    \Filament\Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
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
}