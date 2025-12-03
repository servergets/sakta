<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Placeholder;

class ProductActiveForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                
                Grid::make()
                    ->columns(3)
                    ->schema([
                        Placeholder::make('project_name')
                            ->label('Nama Project')
                            ->content(fn ($record) => e($record->name ?? '-')),
                        Placeholder::make('brand')
                            ->label('Nama Brand')
                            ->content(function ($record) {
                                return e(
                                    $record->product->brand->name ?? 
                                    '-'
                                );
                            }),
                        Placeholder::make('client')
                            ->label('Nama Klien')
                            ->content(function ($record) {
                                return e(
                                    $record->product->client->name ?? 
                                    '-'
                                );
                            }),
                        Placeholder::make('start_date')
                            ->label('Tanggal Mulai')
                            ->content(fn ($record) => e(date('d/m/Y', strtotime($record->start_date)) ?? '-')),
                        TextEntry::make('estimation')
                            ->label('Estimasi')
                            ->html()
                            ->state(function ($record) {
                                if (!$record->start_date) return '-';
                                
                                $endDate = $record->end_date ?? now()->addMonths(4);
                                $months = $record->start_date->diffInMonths($endDate);
                                $endDateFormatted = $record->end_date ? $record->end_date->format('d/m/Y') : '-';
                                
                                return "{$months} bulan <span style='color:red;'>{$endDateFormatted}</span>";
                            }),
                        Placeholder::make('product_type')
                        ->label('Jenis Produk')
                        ->content(function ($record) {
                            return e(
                                $record->product->productType->name ?? 
                                $record->product->category ?? 
                                '-'
                            );
                        }),
                        Placeholder::make('price')
                            ->label('Harga Satuan')
                            ->money('IDR', locale: 'id', decimalPlaces: 0)
                            ->content(function ($record) {
                                return e(
                                    $record->product->price ?? 
                                    '-'
                                );
                            }),
                        Placeholder::make('qty')
                            ->label('Jumlah Product')
                            ->content(function ($record) {
                                return e(
                                    $record->qty ?? 
                                    '-'
                                );
                            }),
                        Placeholder::make('total')
                            ->label('Total Pembelian')
                            ->money('IDR', locale: 'id', decimalPlaces: 0)
                            ->content(function ($record) {
                                return e(
                                    $record->qty*$record->price ?? 
                                    '-'
                                );
                            }),
                        
                    ])
                    ->columnSpanFull(),
                // Ganti Section Progress dengan ini:
                Section::make('Progress Penjualan')
                    ->schema([
                        \Filament\Forms\Components\ViewField::make('progress_visual')
                            ->view('filament.forms.components.progress-stats')
                            ->viewData(function ($record) {
                                return ['record' => $record];
                            }),
                    ]),
        ]);
    }
}