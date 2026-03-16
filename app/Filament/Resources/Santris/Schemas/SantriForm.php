<?php

namespace App\Filament\Resources\Santris\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use App\Models\TahunAjaran;

class SantriForm
{
    public static function schema(): array
    {
        return [
            // SECTION 1: IDENTITAS PRIBADI
            Section::make('IDENTITAS PRIBADI')
                ->schema([
                    Grid::make(2)->schema([
                        Grid::make(1)->schema([
                            TextInput::make('nama_lengkap')->label('Nama Lengkap')->required()->columnSpanFull(),
                            Select::make('jenis_kelamin')->label('Jenis Kelamin')->options(['L' => 'Laki-laki', 'P' => 'Perempuan'])->required(),
                            TextInput::make('tempat_lahir')->label('Tempat Lahir')->required(),
                            DatePicker::make('tanggal_lahir')->label('Tanggal Lahir')->required(),
                        ]),
                        Grid::make(1)->schema([
                            Textarea::make('alamat')->label('Alamat')->rows(5)->required()->columnSpanFull(),
                        ]),
                    ]),
                ])
                ->columnSpanFull(),

            // SECTION 2: DATA AKADEMIK
            Section::make('DATA AKADEMIK')
                ->schema([
                    Grid::make(2)->schema([
                        Grid::make(1)->schema([
                            TextInput::make('nis')->label('NIS')->required()->unique(ignoreRecord: true),
                            Select::make('kamar')->label('Kamar')->required()->options([
                                'A-101' => 'A-101', 'A-102' => 'A-102', 'A-103' => 'A-103', 'A-104' => 'A-104', 'A-105' => 'A-105',
                                'A-201' => 'A-201', 'A-202' => 'A-202', 'A-203' => 'A-203', 'A-204' => 'A-204', 'A-205' => 'A-205',
                                'B-101' => 'B-101', 'B-102' => 'B-102', 'B-103' => 'B-103', 'B-104' => 'B-104', 'B-105' => 'B-105',
                                'B-201' => 'B-201', 'B-202' => 'B-202', 'B-203' => 'B-203', 'B-204' => 'B-204', 'B-205' => 'B-205',
                                'C-101' => 'C-101', 'C-102' => 'C-102', 'C-103' => 'C-103', 'C-104' => 'C-104', 'C-105' => 'C-105',
                                'C-201' => 'C-201', 'C-202' => 'C-202', 'C-203' => 'C-203', 'C-204' => 'C-204', 'C-205' => 'C-205',
                            ])->searchable()->preload(),
                            Select::make('jenjang')->label('Jenjang')->options(['SD' => 'SD', 'SMP' => 'SMP', 'SMA' => 'SMA', 'SMK' => 'SMK'])->required(),
                        ]),
                        Grid::make(1)->schema([
                            Select::make('kelas_id')->relationship('kelas', 'nama_kelas')->label('Kelas')->searchable()->preload()->required() ->reactive(),
                            
                            // ✅ OPSI 1: OTOMATIS BERDASARKAN TAHUN AJARAN AKTIF
                            TextInput::make('tahun_masuk')
                                ->label('Tahun Masuk')
                                ->default(function () {
                                    $tahunAjaranAktif = TahunAjaran::getAktif();
                                    return $tahunAjaranAktif ? $tahunAjaranAktif->tahun_awal : date('Y');
                                })
                                ->disabled()
                                ->dehydrated()
                                ->helperText(function () {
                                    $tahunAjaranAktif = TahunAjaran::getAktif();
                                    return $tahunAjaranAktif 
                                        ? "Otomatis mengikuti Tahun Ajaran aktif: {$tahunAjaranAktif->tahun_ajaran}"
                                        : "Default ke tahun sekarang";
                                })
                                ->required(),

                            Select::make('status')
                                ->label('Status')
                                ->options([
                                    'aktif' => 'Aktif',
                                    'nonaktif' => 'Nonaktif', 
                                    'lulus' => 'Lulus',
                                ])
                                ->required()
                                ->default('aktif'),
                        ]),
                    ]),
                ])
                ->columnSpanFull(),

            // SECTION 3: DATA ORANG TUA
            Section::make('DATA ORANG TUA')
                ->schema([
                    Grid::make(2)->schema([
                        Grid::make(1)->schema([
                            TextInput::make('nama_ayah')->label('Nama Ayah')->required(),
                            TextInput::make('no_hp_ayah')->label('Nomor HP Ayah'),
                            Select::make('pekerjaan_ayah')->label('Pekerjaan Ayah')->options([
                                'PNS' => 'PNS', 'TNI/Polri' => 'TNI/Polri', 'Guru/Dosen' => 'Guru/Dosen', 'Dokter' => 'Dokter',
                                'Perawat' => 'Perawat', 'Wiraswasta' => 'Wiraswasta', 'Pedagang' => 'Pedagang', 
                                'Karyawan Swasta' => 'Karyawan Swasta', 'Buruh' => 'Buruh', 'Petani' => 'Petani',
                                'Nelayan' => 'Nelayan', 'Sopir' => 'Sopir', 'Ibu Rumah Tangga' => 'Ibu Rumah Tangga',
                                'Tidak Bekerja' => 'Tidak Bekerja', 'Lainnya' => 'Lainnya',
                            ])->searchable()->preload(),
                        ]),
                        Grid::make(1)->schema([
                            TextInput::make('nama_ibu')->label('Nama Ibu')->required(),
                            TextInput::make('no_hp_ibu')->label('Nomor HP Ibu'),
                            Select::make('pekerjaan_ibu')->label('Pekerjaan Ibu')->options([
                                'PNS' => 'PNS', 'TNI/Polri' => 'TNI/Polri', 'Guru/Dosen' => 'Guru/Dosen', 'Dokter' => 'Dokter',
                                'Perawat' => 'Perawat', 'Wiraswasta' => 'Wiraswasta', 'Pedagang' => 'Pedagang', 
                                'Karyawan Swasta' => 'Karyawan Swasta', 'Buruh' => 'Buruh', 'Petani' => 'Petani',
                                'Nelayan' => 'Nelayan', 'Sopir' => 'Sopir', 'Ibu Rumah Tangga' => 'Ibu Rumah Tangga',
                                'Tidak Bekerja' => 'Tidak Bekerja', 'Lainnya' => 'Lainnya',
                            ])->searchable()->preload(),
                        ]),
                    ]),
                    Textarea::make('alamat_ortu')->label('Alamat Orang Tua')->rows(3)->columnSpanFull(),
                ])
                ->columnSpanFull(),
        ];
    }
}