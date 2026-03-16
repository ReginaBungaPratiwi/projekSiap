<?php

namespace App\Filament\Resources\Jurusans\Pages;

use App\Filament\Resources\Jurusans\JurusanResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewJurusan extends ViewRecord
{
    protected static string $resource = JurusanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('Edit'),
                
            Actions\Action::make('back')
                ->label('Kembali ke Daftar Jurusan')
                ->url(JurusanResource::getUrl('index'))
                ->color('gray'),
        ];
    }
}