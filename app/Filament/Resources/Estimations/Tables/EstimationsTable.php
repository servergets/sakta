<?php

namespace App\Filament\Resources\Estimations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;

class EstimationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('estimation_long')
                    ->label('Lama Estimasi')
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Satuan')
                    ->searchable(),
                // TextColumn::make('client.name')
                //     ->searchable(),
                // TextColumn::make('project.name')
                //     ->searchable(),
                // TextColumn::make('estimation_date')
                //     ->date()
                //     ->sortable(),
                // TextColumn::make('valid_until')
                //     ->date()
                //     ->sortable(),
                // TextColumn::make('total_amount')
                //     ->numeric()
                //     ->sortable(),
                // TextColumn::make('status')
                //     ->badge(),
                // TextColumn::make('created_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // TextColumn::make('updated_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->modalHeading('Edit Estimasi')
                    ->modalWidth('md')
                    ->modalSubmitActionLabel('Simpan') 
                    ->modalCancelActionLabel('Batal')
                    ->modalCancelAction(fn ($action) => $action->color('danger')->outlined())
                    ->modalFooterActionsAlignment('right'),
                
                DeleteAction::make()
                    ->modalHeading('Hapus  Estimasi')
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
