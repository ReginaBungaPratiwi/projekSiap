<?php

namespace App\Filament\Resources\RekapNilaiSikaps\Pages;

use App\Filament\Resources\RekapNilaiSikaps\RekapNilaiSikapResource;
use App\Models\User;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListRekapNilaiSikaps extends ListRecords
{
    protected static string $resource = RekapNilaiSikapResource::class;

    public function getTitle(): string
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user && $user->hasRole('ustadz')) {
            return 'Laporan Nilai Sikap';
        }

        return 'Laporan Nilai Akhlak Dan Sikap';
    }

    public function getSubheading(): ?string
    {
        return 'List Nilai Akhlak & Sikap';
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
