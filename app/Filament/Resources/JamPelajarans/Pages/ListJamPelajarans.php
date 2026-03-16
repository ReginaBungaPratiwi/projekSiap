<?php

namespace App\Filament\Resources\JamPelajarans\Pages;

use App\Filament\Resources\JamPelajarans\JamPelajaranResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJamPelajarans extends ListRecords
{
    protected static string $resource = JamPelajaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('New Jam Pelajaran'),
        ];
    }
}
