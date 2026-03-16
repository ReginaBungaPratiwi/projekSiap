<?php

namespace App\Filament\Resources\Santris;

use App\Filament\Resources\Santris\Pages;
use App\Models\Santri;
use Filament\Resources\Resource;

class SantriResource extends Resource
{
    protected static ?string $model = Santri::class;

    // TIDAK ADA PROPERTY LAIN SELAIN MODEL

    public static function getNavigationLabel(): string
    {
        return 'Santris';
    }

    public static function getModelLabel(): string
    {
        return 'Santri';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Santris';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Data Master';
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-users';
    }

    public static function form($form)
    {
        return $form->schema([
            // Schema dasar dulu
            \Filament\Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
        ]);
    }

    public static function table($table)
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('name'),
            ])
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