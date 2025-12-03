<?php

namespace App\Filament\Resources\ProductTypes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Jenis Produk')
                    ->columnSpanFull()
                    ->required(),
                // TextInput::make('code')
                //     ->required(),
                // Textarea::make('description')
                //     ->default(null)
                //     ->columnSpanFull(),
                // Toggle::make('is_active')
                //     ->required(),
            ]);
    }
}
