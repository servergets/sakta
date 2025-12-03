<?php

namespace App\Filament\Resources\Brands\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\FileUpload;

class BrandsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Gambar')
                    // ->circleCropper()
                    ->imageHeight(40)
                    ->circular(),
                TextColumn::make('name')
                    ->label('Nama Brand')
                    ->searchable(),
                TextColumn::make('client.name')
                    ->label('Klien')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->modalHeading('Edit Brand')
                    ->modalWidth('md')
                    ->modalSubmitActionLabel('Simpan') 
                    ->modalCancelActionLabel('Batal')
                    ->modalCancelAction(fn ($action) => $action->color('danger')->outlined())
                    ->modalFooterActionsAlignment('right'),
                
                DeleteAction::make()
                    ->modalHeading('Hapus  Brand')
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
