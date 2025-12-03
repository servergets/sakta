<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Facades\Storage;
use Filament\Tables\Columns\ImageColumn;


class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Gambar')
                    ->imageHeight(40)
                    ->circular(),
                TextColumn::make('name')
                    ->label('Nama Produk')
                    ->description(fn ($record) => $record->productType?->name ?? '-')
                    ->searchable(),
                TextColumn::make('brand.name')
                    ->label('Brand')
                    ->searchable()
                    ->formatStateUsing(function ($state, $record) {
                        $brand = $record->brand;
                       
                        if ($brand && $brand->image) {
                            // Buat temporary signed URL
                            $imageUrl = Storage::temporaryUrl(
                                $brand->image, 
                                now()->addMinutes(5) // expires dalam 5 menit
                            );
                        } else {
                            $imageUrl = asset('storage/images/default-brand.png');
                        }
                        
                        return "
                            <div class='fi-circular fi-ta-image'>
                                <img src='{$imageUrl}' 
                                    alt='{$state}' style='height: 40px; width: 40px;'>
                                <span>{$state}</span>
                            </div>
                        ";
                    })
                    ->html()
                    ->description(fn ($record) => $record->brand->client?->name ?? '-'),
                TextColumn::make('productUnit.name')
                    ->label('Satuan')
                    ->searchable(),
                TextColumn::make('price')
                    ->money('IDR', locale: 'id', decimalPlaces: 0)
                    ->label('HPP')
                    ->sortable(),
                TextColumn::make('ppn')
                    ->suffix('%')
                    ->sortable(),
                TextColumn::make('price_selling')
                    ->money('IDR', locale: 'id', decimalPlaces: 0)
                    ->label('Harga Jual')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->modalHeading('Edit Produk')
                    ->modalSubmitActionLabel('Simpan') 
                    ->modalCancelActionLabel('Batal')
                    ->modalCancelAction(fn ($action) => $action->color('danger')->outlined())
                    ->modalFooterActionsAlignment('right'),
                
                DeleteAction::make()
                    ->modalHeading('Hapus Produk')
                    ->modalWidth('sm')
                    ->modalSubmitActionLabel('Ya, Hapus')
                    ->modalCancelActionLabel('Batal'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
