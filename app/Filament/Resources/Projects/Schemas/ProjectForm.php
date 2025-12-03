<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\{
    DatePicker, Select, TextInput, Textarea, FileUpload, Hidden, Placeholder
};
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Forms\Set;
use Filament\Schemas\Components\Grid;


class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([              
                FileUpload::make('project_photo')
                    ->label('Foto Project')
                    ->image()
                    ->imageEditor()
                    ->acceptedFileTypes(['image/jpeg', 'image/png'])
                    ->directory(function ($record) {
                        if ($record && $record->id) {
                            return "project/{$record->id}";
                        }
                        return 'project/temp';
                    })
                    ->maxSize(2048)
                    ->columnSpanFull(),
                TextInput::make('name')
                    ->label('Nama Project')
                    ->columnSpanFull()
                    ->required(),
                Select::make('product_id')
                    ->label('Pilih Produk')
                    ->relationship('product', 'name')
                    ->columnSpanFull()
                    ->searchable()
                    ->preload()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (callable $get, callable $set) {
                        $productId = $get('product_id');
                        if ($productId) {
                            $product = \App\Models\Product::find($productId);
                            $set('brand_id', $product?->brand_id);
                            $set('client_id', $product?->client_id);
                            $set('price', $product?->price);
                        } else {
                            $set('brand_id', null);
                            $set('client_id', null);
                            $set('price', null);
                        }
                    }),
                TextInput::make('qty')
                    ->label('Kuantiti Produk')
                    ->numeric()
                    ->required()
                    ->live(),
                TextInput::make('price')
                    ->label('Harga Satuan')
                    ->disabled()
                    ->dehydrated()
                    ->numeric()
                    ->required(),
                Select::make('brand_id')
                    ->label('Brand')
                    ->relationship('brand', 'name')
                    ->disabled()
                    ->dehydrated()
                    ->required(),
                Select::make('client_id')
                    ->label('Pilih Klien')
                    ->relationship('client', 'name')
                    ->disabled()
                    ->dehydrated()
                    ->required(),
                DatePicker::make('start_date')
                    ->label('Tanggal Mulai')
                    ->required(),
                DatePicker::make('end_date')
                    ->label('Tanggal Berakhir'),
                TextInput::make('max_buyer')
                    ->label('Maksimal Buyer')
                    ->numeric()
                    ->required(),
                TextInput::make('min_purchase')
                    ->label('Minimal Pembelian')
                    ->numeric()
                    ->required(),
                
                Grid::make(3)
                        ->schema([
                    TextInput::make('margin_sakta')
                        ->label('Margin Sakta (%)')
                        ->numeric()
                        ->required()
                        ->default(3)
                        ->suffix('%')
                        ->live()
                        ->afterStateUpdated(fn ($state, Forms\Set $set) => 
                            static::calculateTotalPembelian($set)
                        ),
                        
                    TextInput::make('margin_sales')
                        ->label('Margin Sales (%)')
                        ->numeric()
                        ->required()
                        ->default(50)
                        ->suffix('%')
                        ->live()
                        ->afterStateUpdated(fn ($state, Forms\Set $set) => 
                            static::calculateTotalPembelian($set)
                        ),
                        
                    TextInput::make('margin_buyer')
                        ->label('Margin Buyer (%)')
                        ->numeric()
                        ->required()
                        ->default(47)
                        ->suffix('%')
                        ->live()
                        ->afterStateUpdated(fn ($state, Forms\Set $set) => 
                            static::calculateTotalPembelian($set)
                        ),
                ])
                    ->columnSpanFull(),
                Placeholder::make('total_purchase')
                    ->label('Total Pembelian')
                    ->content(fn ($get) => 
                        'Rp ' . number_format(
                            (($get('qty') ?? 0) * ($get('price') ?? 0)),
                            0,
                            ',',
                            '.'
                        )
                    )
                    ->reactive()
                    ->columnSpanFull(),
            ]);
    }
}
