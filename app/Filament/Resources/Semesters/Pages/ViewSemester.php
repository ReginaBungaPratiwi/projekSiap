<?php

namespace App\Filament\Resources\Semesters\Pages;

use App\Filament\Resources\Semesters\SemesterResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSemester extends ViewRecord
{
    protected static string $resource = SemesterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->label('Edit'),
            Actions\Action::make('back')
                ->label('Kembali ke Daftar Semester')
                ->url(SemesterResource::getUrl('index'))
                ->color('gray'),
        ];
    }
}
