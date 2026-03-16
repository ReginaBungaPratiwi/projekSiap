<?php

namespace App\Filament\Resources\JamPelajarans\Pages;

use App\Filament\Resources\JamPelajarans\JamPelajaranResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewJamPelajaran extends ViewRecord
{
    protected static string $resource = JamPelajaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->label('Edit'),
            Actions\Action::make('back')
                ->label('Kembali ke Daftar Jam Pelajaran')
                ->url(JamPelajaranResource::getUrl('index'))
                ->color('gray'),
        ];
    }
}
