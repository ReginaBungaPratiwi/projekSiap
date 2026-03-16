<?php

namespace App\Filament\Resources\Santris; // ✅ PERBAIKI NAMESPACE

use App\Filament\Resources\Santris\Pages;
use App\Filament\Resources\Santris\Schemas\SantriForm;
use App\Filament\Resources\Santris\Tables\SantrisTable;
use App\Models\Santri;
use Filament\Resources\Resource;

class SantriResource extends Resource
{
    protected static ?string $model = Santri::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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