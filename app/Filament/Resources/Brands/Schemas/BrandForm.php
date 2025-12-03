<?php

namespace App\Filament\Resources\Brands\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;

class BrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('image')
                    ->image()
                    ->acceptedFileTypes(['image/jpeg', 'image/png'])
                    ->directory(function ($record) {
                        if ($record && $record->id) {
                            return "brand/{$record->id}";
                        }
                        return 'brand/temp';
                    })
                    ->columnSpanFull()
                    ->maxSize(2048) // dalam kilobytes (2MB)
                    ->helperText('Format: JPG, PNG. Maksimal 2MB')
                    ->columnSpanFull()
                    ->imageEditor(),
                TextInput::make('name')
                    ->label('Nama Brand')
                    ->columnSpanFull()
                    ->required(),
                Select::make('client_id')
                    ->relationship('client', 'name')
                    ->columnSpanFull()
                    ->required(),
            ]);
    }
}
