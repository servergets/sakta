<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductMutationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('mutation_type_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('mutation_date')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('reference_code')
                    ->searchable(),
                TextColumn::make('qty_in')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('qty_out')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('unit_price')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_value')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('current_stock')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_by')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('updated_by')
                    ->numeric()
                    ->sortable(),
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
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
