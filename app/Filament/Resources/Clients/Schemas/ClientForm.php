<?php

namespace App\Filament\Resources\Clients\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Forms\Components\FileUpload;

class ClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('image')
                    ->image()
                    // ->acceptedFileTypes(['image/jpeg', 'image/png', 'application/pdf'])
                    ->acceptedFileTypes(['image/jpeg', 'image/png'])
                    ->directory(function ($record) {
                        if ($record && $record->id) {
                            return "clients/{$record->id}/logo";
                        }
                        return 'clients/temp/logo';
                    })
                    ->columnSpanFull()
                    ->maxSize(2048) // dalam kilobytes (2MB)
                    ->helperText('Format: JPG, PNG. Maksimal 2MB')
                    ->imageEditor(),
                TextInput::make('name')
                    ->columnSpanFull()
                    ->required(),
                
                // TextInput::make('email')
                //     ->label('Email address')
                //     ->email()
                //     ->default(null),
                // TextInput::make('phone')
                //     ->tel()
                //     ->default(null),
                // Textarea::make('address')
                //     ->default(null)
                //     ->columnSpanFull(),
                // TextInput::make('pic_name')
                //     ->default(null),
                // TextInput::make('pic_phone')
                //     ->tel()
                //     ->default(null),
                // Toggle::make('is_active')
                //     ->required(),
            ]);
    }
}
