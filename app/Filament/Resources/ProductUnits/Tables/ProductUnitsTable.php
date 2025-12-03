<?php

namespace App\Filament\Resources\ProductUnits\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;


class ProductUnitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                // TextColumn::make('symbol')
                //     ->searchable(),
                // IconColumn::make('is_active')
                //     ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->modalHeading('Edit Jenis Produk')
                    ->modalWidth('md')
                    ->modalSubmitActionLabel('Simpan') 
                    ->modalCancelActionLabel('Batal')
                    ->modalCancelAction(fn ($action) => $action->color('danger')->outlined())
                    ->modalFooterActionsAlignment('right'),
                
                DeleteAction::make()
                    ->modalHeading('Hapus  Jenis Produk')
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
