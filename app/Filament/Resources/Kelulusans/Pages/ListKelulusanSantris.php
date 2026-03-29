<?php

namespace App\Filament\Resources\Kelulusans\Pages;

use App\Filament\Resources\Kelulusans\KelulusanResource;
use App\Models\Kelas;
use App\Models\Kelulusan;
use App\Models\Santri;
use App\Models\TahunAjaran;
use App\Models\User;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Actions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ListKelulusanSantris extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = KelulusanResource::class;
    protected string $view = 'filament.resources.kelulusans.pages.list-kelulusan-santris';

    public Kelas $record;
    public TahunAjaran $tahunAjaran;

    public function mount(int|Kelas $record, int|TahunAjaran $tahun_ajaran): void
    {
        $this->record = $record instanceof Kelas
            ? $record
            : Kelas::findOrFail($record);

        $this->tahunAjaran = $tahun_ajaran instanceof TahunAjaran
            ? $tahun_ajaran
            : TahunAjaran::findOrFail($tahun_ajaran);

        // Cek otorisasi: ustadz hanya bisa akses kelas yang dia walikan
        /** @var User|null $user */
        $user = Auth::user();
        if ($user && $user->hasRole('ustadz') && $user->ustadz_id) {
            if ((int) $this->record->wali_kelas_id !== (int) $user->ustadz_id) {
                abort(403, 'Anda tidak memiliki akses ke kelas ini.');
            }
        }
    }

    public function getTitle(): string
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user && $user->hasRole('ustadz')) {
            return 'Kelulusan Santri';
        }

        return 'List Kelulusan Santri';
    }

    public function getSubheading(): ?string
    {
        return 'List Daftar Siswa';
    }

    public function getBreadcrumbs(): array
    {
        return [
            KelulusanResource::getUrl() => 'Kelulusan',
            '#' => 'List Santri',
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Santri::query()
                    ->where('kelas_id', $this->record->id)
                    ->orderBy('nama_lengkap', 'asc')
            )
            ->columns([
                TextColumn::make('no')
                    ->label('NO')
                    ->rowIndex()
                    ->sortable(false),

                TextColumn::make('nis')
                    ->label('NIS')
                    ->searchable()
                    ->sortable(false),

                TextColumn::make('nama_lengkap')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(false),

                TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->sortable(false),

                TextColumn::make('tahun_ajaran_display')
                    ->label('Tahun Ajaran')
                    ->state(fn() => $this->tahunAjaran->tahun_ajaran),

                TextColumn::make('status_kelulusan')
                    ->label('Status')
                    ->state(function (Santri $record) {
                        $kelulusan = Kelulusan::where('santri_id', $record->id)
                            ->where('kelas_id', $this->record->id)
                            ->where('tahun_ajaran_id', $this->tahunAjaran->id)
                            ->first();

                        return $kelulusan?->status ?? 'belum_ditentukan';
                    })
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'lulus' => 'success',
                        'tidak_lulus' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'lulus' => 'Lulus',
                        'tidak_lulus' => 'Tidak Lulus',
                        default => 'Belum di tentukan',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'lulus' => 'heroicon-o-check-circle',
                        'tidak_lulus' => 'heroicon-o-x-circle',
                        default => 'heroicon-o-x-circle',
                    }),
            ])
            ->actions([
                // View action - for admin only
                Actions\Action::make('view')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn(Santri $record) => KelulusanResource::getUrl('view-santri', [
                        'record' => $this->record->id,
                        'santri' => $record->id,
                        'tahun_ajaran' => $this->tahunAjaran->id,
                    ]))
                    ->visible(fn(): bool => $this->isAdmin()),

                // Edit action - for admin only
                Actions\Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil-square')
                    ->color('warning')
                    ->url(fn(Santri $record) => KelulusanResource::getUrl('edit-santri', [
                        'record' => $this->record->id,
                        'santri' => $record->id,
                        'tahun_ajaran' => $this->tahunAjaran->id,
                    ]))
                    ->visible(fn(): bool => $this->isAdmin()),

                // Tentukan Kelulusan - for ustadz wali kelas only
                Actions\Action::make('tentukan_kelulusan')
                    ->label('Tentukan Kelulusan')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->color('primary')
                    ->url(fn(Santri $record) => KelulusanResource::getUrl('edit-santri', [
                        'record' => $this->record->id,
                        'santri' => $record->id,
                        'tahun_ajaran' => $this->tahunAjaran->id,
                    ]))
                    ->visible(function (): bool {
                        /** @var User|null $user */
                        $user = Auth::user();

                        // Hanya tampil untuk ustadz yang merupakan wali kelas dari kelas ini
                        if (!$user || !$user->hasRole('ustadz') || !$user->ustadz_id) {
                            return false;
                        }

                        return (int) $this->record->wali_kelas_id === (int) $user->ustadz_id;
                    }),
            ])
            ->defaultSort('id', 'asc');
    }

    /**
     * Check if current user is admin
     */
    protected function isAdmin(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        return $user && ($user->hasRole('super_admin') || $user->hasRole('admin'));
    }

    /**
     * Check if current user is ustadz wali kelas of this class
     */
    protected function isUstadz(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            return false;
        }

        if (!$user->hasRole('ustadz')) {
            return false;
        }

        if (!$user->ustadz_id) {
            return false;
        }

        // Pastikan record sudah di-set
        if (!isset($this->record) || !$this->record) {
            return false;
        }

        // Cek apakah user adalah wali kelas dari kelas ini
        $waliKelasId = $this->record->wali_kelas_id;
        $userUstadzId = $user->ustadz_id;

        return (int) $waliKelasId === (int) $userUstadzId;
    }
}
