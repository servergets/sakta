<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Placeholder;

class ProductMutationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            // 🔹 Baris Atas: Header Pemesanan
            Select::make('client_id')
                ->relationship('client', 'name')
                ->label('Nama Pemasok')
                ->searchable()
                ->required()
                ->preload()
                ->columnSpan(1),

            TextInput::make('reference_code')
                ->label('Nomor Formulir Pesanan')
                ->disabled()
                ->dehydrated()
                ->default(fn () => 'PO/' . date('dmY') . '/....')
                ->helperText('Kode ini akan diisi otomatis setelah data disimpan.')
                ->columnSpan(1),

            DatePicker::make('mutation_date')
                ->label('Tanggal Order')
                ->default(now())
                ->required()
                ->columnSpan(1),

            // 🔹 Daftar Produk yang Dipesan
            Repeater::make('items')
                ->label('Daftar Produk')
                ->createItemButtonLabel('Tambah Produk')
                ->columns(7) // Diubah menjadi 7 kolom untuk menampung No.
                ->schema([
                    // Kolom No.
                    TextInput::make('index')
                        ->label('No.')
                        ->disabled()
                        ->dehydrated()
                        ->default(fn ($state, $component) => 
                            (string) ($component->getState() ? array_search($state, $component->getState()) + 1 : 1)
                        )
                        ->columnSpan(1),

                    Select::make('product_id')
                        ->label('Nama')
                        ->relationship('product', 'name')
                        ->searchable()
                        ->required()
                        ->columnSpan(2)
                        ->preload()
                        ->reactive(),

                    Select::make('brand_id')
                        ->label('Brand')
                        ->relationship('product.brand', 'name')
                        ->disabled()
                        ->dehydrated(false)
                        ->columnSpan(1),

                    TextInput::make('qty_in')
                        ->label('Jumlah Pesanan')
                        ->numeric()
                        ->default(0)
                        ->columnSpan(1)
                        ->reactive()
                        ->afterStateUpdated(fn ($state, callable $set, callable $get) => 
                            $set('total_value', $state * ($get('unit_price') ?? 0))
                        ),

                    // Kolom Satuan (Botol)
                    TextInput::make('unit')
                        ->label('Satuan')
                        ->disabled()
                        ->dehydrated()
                        ->default('Botol')
                        ->columnSpan(1),

                    TextInput::make('unit_price')
                        ->label('Harga Satuan')
                        ->prefix('Rp')
                        ->numeric()
                        ->default(0)
                        ->columnSpan(1)
                        ->reactive()
                        ->afterStateUpdated(fn ($state, callable $set, callable $get) => 
                            $set('total_value', ($get('qty_in') ?? 0) * $state)
                        ),

                    TextInput::make('total_value')
                        ->label('Total Harga')
                        ->prefix('Rp')
                        ->numeric()
                        ->disabled()
                        ->dehydrated()
                        ->columnSpan(1),
                ])
                ->collapsible()
                ->defaultItems(1)
                ->columnSpanFull(),

            // 🔹 Keterangan tambahan (opsional)
            Textarea::make('description')
                ->label('Keterangan Tambahan')
                ->columnSpanFull(),
        ]);
    }
}