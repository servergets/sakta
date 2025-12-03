<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Forms\Components\FileUpload;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // TextInput::make('code')
                //     ->required(),
                FileUpload::make('image')
                    ->image()
                    ->columnSpanFull()
                    ->imageEditor(),
                TextInput::make('name')
                    ->label('Nama Produk')
                    ->columnSpanFull()
                    ->required(),
                Select::make('product_type_id')
                    ->relationship('productType', 'name')
                    ->required(),
                Select::make('product_unit_id')
                    ->relationship('productUnit', 'name')
                    ->required(),
                Select::make('brand_id')
                    ->relationship('brand', 'name')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $brand = \App\Models\Brand::find($state);
                            $set('client_id', $brand?->client_id);
                        }
                    }),
                Select::make('client_id')
                    ->relationship('client', 'name')
                    ->disabled()
                    ->dehydrated(),
                TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->live(onBlur: true) // Gunakan live() untuk trigger update
                    ->afterStateUpdated(function ($state, callable $set, $get) {
                        self::calculatePriceSelling($set, $get);
                    }),
                TextInput::make('ppn')
                    ->label('PPN (%)')
                    ->required()
                    ->numeric()
                    ->default(11)
                    ->live(onBlur: true) // Gunakan live() untuk trigger update
                    ->afterStateUpdated(function ($state, callable $set, $get) {
                        self::calculatePriceSelling($set, $get);
                    }),
                TextInput::make('price_selling')
                    ->label('Harga Jual (dengan PPN)')
                    ->required()
                    ->numeric()
                    ->columnSpanFull()
                    ->default(0)
                    ->disabled() // readonly
                    ->dehydrated() // Pastikan nilai tetap disimpan meski disabled
                    ->prefix('Rp'),
                Toggle::make('is_active')
                    ->label('Status Aktif')
                    ->default(true) // Default true saat create baru
                    ->onColor('success')
                    ->offColor('danger')
                    ->onIcon('heroicon-o-check')
                    ->offIcon('heroicon-o-x-mark')
                    ->inline(false)
                    ->helperText('Nonaktifkan jika produk tidak aktif'),
            ]);
    }

    // Helper method untuk menghitung price_selling
    private static function calculatePriceSelling(callable $set, $get): void
    {
        $price = (float) ($get('price') ?? 0);
        $ppn = (float) ($get('ppn') ?? 0);
        
        // Hitung price_selling = price + (price * ppn / 100)
        $priceSelling = $price + ($price * $ppn / 100);
        
        $set('price_selling', round($priceSelling, 2));
    }
}