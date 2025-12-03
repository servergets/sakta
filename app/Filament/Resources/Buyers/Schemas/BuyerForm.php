<?php

namespace App\Filament\Resources\Buyers\Schemas;

use Filament\Forms\Components\{
    TextInput,
    Textarea,
    FileUpload,
    Select,
    DatePicker, 
};
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;


class BuyerForm
{
    public static function configure(Schema $schema): Schema
    {
        
        return $schema
            ->components([
                Section::make('Informasi Umum')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make()
                            ->columns(3)
                            ->schema([
                                FileUpload::make('profile_photo')
                                    ->label('Foto Profil')
                                    ->image()
                                    ->imageEditor()
                                    ->acceptedFileTypes(['image/jpeg', 'image/png'])
                                    ->directory(function ($record) {
                                        if ($record && $record->id) {
                                            return "buyers/{$record->id}";
                                        }
                                        return 'buyers/temp';
                                    })
                                    ->maxSize(2048),
                            ]),

                        Grid::make()
                            ->columns(4)
                            ->schema([
                                TextInput::make('buyer_name')
                                    ->label('Nama Buyer')
                                    ->ascii()
                                    ->required(),
                                TextInput::make('citizenship')
                                    ->label('Kewarganegaraan')
                                    ->required(),
                                DatePicker::make('birth_date')
                                    ->label('Tanggal Lahir')
                                    ->required(),
                                Select::make('gender_id')
                                    ->label('Jenis Kelamin')
                                    ->relationship('gender', 'name')
                                    ->required(),
                            ]),

                        Grid::make()
                            ->columns(3)
                            ->schema([
                                Select::make('religion_id')
                                    ->label('Agama')
                                    ->relationship('religion', 'name')
                                    ->required(),
                                TextInput::make('id_number')
                                    ->label('No. KTP')
                                    ->maxLength(16)
                                    ->numeric()
                                    ->required()
                                    ->unique(ignoreRecord: true),
                                TextInput::make('tax_number')
                                    ->label('NPWP')
                                    ->nullable(),
                            ]),

                        Grid::make()
                            ->columns(3)
                            ->schema([
                                TextInput::make('phone')
                                    ->label('Nomor Telepon')
                                    ->tel()
                                    ->nullable(),
                                TextInput::make('company_name')
                                    ->label('Nama Kantor')
                                    ->required(),
                                TextInput::make('email')
                                    ->label('Email')
                                    ->email()
                                    ->required()
                                    ->unique(ignoreRecord: true),
                            ]),
                    ]),

                Section::make('Rekening')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make()
                            ->columns(3)
                            ->schema([
                                TextInput::make('account_holder_name')
                                    ->label('Nama Pemilik Rekening')
                                    ->required(),
                                Select::make('bank_id')
                                    ->label('Bank')
                                    ->relationship('bank', 'name')
                                    ->required(),
                                TextInput::make('account_number')
                                    ->label('Nomor Rekening')
                                    ->minLength(10)
                                    ->maxLength(17)
                                    ->numeric()
                                    ->required(),
                            ]),
                    ]),

                Section::make('Alamat Rumah')
                ->columnSpanFull()
                ->schema([
                    Grid::make()
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        Grid::make()
                        ->columns(2)
                        ->schema([
                            Textarea::make('home_address')
                                ->label('Alamat Lengkap')
                                ->required()
                                ->columnSpanFull(),
                               
                        ]), 
                        
                        Grid::make()
                        ->columns(2)
                        ->schema([    
                            Select::make('home_country_id')
                                ->label('Negara')
                                ->relationship('homeCountry', 'name')
                                ->required(),
                            Select::make('home_province_id')
                                ->label('Provinsi')
                                ->options(\App\Models\MasterProvince::pluck('province_name', 'id'))
                                ->reactive()
                                ->afterStateUpdated(fn (callable $set) => $set('home_city_id', null))
                                ->required()
                                ->searchable(),
                            Select::make('home_city_id')
                                ->label('Kota / Kabupaten')
                                ->options(function (callable $get) {
                                    $province = $get('home_province_id');
                                    if (!$province) return [];
                                    return \App\Models\MasterCity::where('province_id', $province)
                                        ->pluck('city_name', 'id');
                                })
                                ->reactive()
                                ->afterStateUpdated(fn (callable $set) => $set('home_district_id', null))
                                ->required()
                                ->searchable(),
                            Select::make('home_district_id')
                                ->label('Kecamatan')
                                ->options(function (callable $get) {
                                    $city = $get('home_city_id');
                                    if (!$city) return [];
                                    return \App\Models\MasterDistrict::where('city_id', $city)
                                        ->pluck('district_name', 'id');
                                })
                                ->reactive()
                                ->afterStateUpdated(fn (callable $set) => $set('home_subdistrict_id', null))
                                ->required()
                                ->searchable(),

                            Select::make('home_subdistrict_id')
                                ->label('Kelurahan / Desa')
                                ->options(function (callable $get) {
                                    $district = $get('home_district_id');
                                    if (!$district) return [];
                                    return \App\Models\MasterSubdistrict::where('district_id', $district)
                                        ->pluck('subdistrict_name', 'id');
                                })
                                ->required()
                                ->searchable(),
                            TextInput::make('home_postal_code')
                                ->label('Kode Pos'),
                        ]),  
                    ]),  
                ]),
               
                
                Section::make('Alamat Kantor')
                ->columnSpanFull()
                ->schema([
                    Grid::make()
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        Grid::make()
                        ->columns(2)
                        ->schema([
                            Textarea::make('office_address')
                                ->label('Alamat Lengkap')
                                ->required()
                                ->columnSpanFull(),
                        ]), 
                        Grid::make()
                        ->columns(2)
                        ->schema([
                            Select::make('office_country_id')
                                ->label('Negara')
                                ->relationship('officeCountry', 'name')
                                ->required(),
                            Select::make('office_province_id')
                                ->label('Provinsi')
                                ->options(\App\Models\MasterProvince::pluck('province_name', 'id'))
                                ->reactive()
                                ->afterStateUpdated(fn (callable $set) => $set('office_city_id', null))
                                ->required()
                                ->searchable(),
                            Select::make('office_city_id')
                                ->label('Kota / Kabupaten')
                                ->options(function (callable $get) {
                                    $province = $get('office_province_id');
                                    if (!$province) return [];
                                    return \App\Models\MasterCity::where('province_id', $province)
                                        ->pluck('city_name', 'id');
                                })
                                ->reactive()
                                ->afterStateUpdated(fn (callable $set) => $set('office_district_id', null))
                                ->required()
                                ->searchable(),
                            Select::make('office_district_id')
                                ->label('Kecamatan')
                                ->options(function (callable $get) {
                                    $city = $get('office_city_id');
                                    if (!$city) return [];
                                    return \App\Models\MasterDistrict::where('city_id', $city)
                                        ->pluck('district_name', 'id');
                                })
                                ->reactive()
                                ->afterStateUpdated(fn (callable $set) => $set('office_subdistrict_id', null))
                                ->required()
                                ->searchable(),

                            Select::make('office_subdistrict_id')
                                ->label('Kelurahan / Desa')
                                ->options(function (callable $get) {
                                    $district = $get('office_district_id');
                                    if (!$district) return [];
                                    return \App\Models\MasterSubdistrict::where('district_id', $district)
                                        ->pluck('subdistrict_name', 'id');
                                })
                                ->required()
                                ->searchable(),
                            TextInput::make('office_postal_code')
                                ->label('Kode Pos'),
                        ]),  
                    ]),  
                ]),

                Section::make('Dokumen Pendukung')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make()
                            ->columns(2)
                            ->schema([
                                FileUpload::make('id_card_file')
                                    ->label('Unggah KTP')
                                    ->image()
                                    ->acceptedFileTypes(['image/jpeg', 'image/png'])
                                    ->directory(function ($record) {
                                        if ($record && $record->id) {
                                            return "buyers/{$record->id}/ktp";
                                        }
                                        return 'buyers/temp';
                                    })
                                    ->required()
                                    ->nullable(),
                                FileUpload::make('tax_card_file')
                                    ->label('Unggah NPWP')
                                    ->image()
                                    ->acceptedFileTypes(['image/jpeg', 'image/png'])
                                    ->directory(function ($record) {
                                        if ($record && $record->id) {
                                            return "buyers/{$record->id}/npwp";
                                        }
                                        return 'buyers/temp';
                                    })
                                    ->required()
                                    ->nullable(),
                            ]),
                    ]),
            ]);
    }
}