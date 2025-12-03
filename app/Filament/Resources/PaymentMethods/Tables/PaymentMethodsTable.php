<?php

namespace App\Filament\Resources\PaymentMethods\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PaymentMethodsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('bank_name')
                    ->label('Bank')
                    ->searchable(),
                TextColumn::make('account_name')
                    ->label('Nama Akun')
                    ->searchable(),
                TextColumn::make('account_no')
                    ->label('Nomor Rekening')
                    ->searchable(),
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
                EditAction::make(),
            ])
            
            ->actions([
                EditAction::make()
                    ->modalHeading('Edit Akun Bank')
                    // ->modalWidth('md')
                    // ->modalSubmitActionLabel('Simpan') 
                    // ->modalCancelActionLabel('Batalkan')
                    // ->modalCancelAction(fn ($action) => $action->color('danger')->outlined())
                    // ->modalFooterActionsAlignment('right')
                    ,
                
                DeleteAction::make()
                    ->modalHeading('Hapus Metode Pembayaran')
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
