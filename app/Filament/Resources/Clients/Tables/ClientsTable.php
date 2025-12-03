<?php

namespace App\Filament\Resources\Clients\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Actions\DeleteAction;

class ClientsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                ->label('Gambar'),
                TextColumn::make('name')
                    ->label('Nama Klien')
                    ->searchable(),
                // TextColumn::make('email')
                //     ->label('Email address')
                //     ->searchable(),
                // TextColumn::make('phone')
                //     ->searchable(),
                // TextColumn::make('pic_name')
                //     ->searchable(),
                // TextColumn::make('pic_phone')
                //     ->searchable(),
                // IconColumn::make('is_active')
                //     ->boolean(),
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
