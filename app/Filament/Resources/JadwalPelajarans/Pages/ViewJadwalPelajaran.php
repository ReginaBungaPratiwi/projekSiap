<?php

namespace App\Filament\Resources\JadwalPelajarans\Pages;

use App\Filament\Resources\JadwalPelajarans\JadwalPelajaranResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewJadwalPelajaran extends ViewRecord
{
    protected static string $resource = JadwalPelajaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->label('Edit'),
            Actions\Action::make('back')
                ->label('Kembali ke Daftar Jadwal Pelajaran')
                ->url(JadwalPelajaranResource::getUrl('index'))
                ->color('gray'),
        ];
    }
}
