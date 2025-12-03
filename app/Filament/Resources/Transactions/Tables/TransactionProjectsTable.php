<?php

namespace App\Filament\Resources\Transactions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use App\Filament\Resources\Transactions\Schemas\TransactionProjectDetailModal;

class TransactionProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('start_date')
                    ->date()
                    ->label('Tanggal')
                    ->sortable()
                    ->date('d/m/Y'),
                TextColumn::make('buyer.buyer_name')
                    ->label('Nama Buyer')
                    ->searchable(),
                TextColumn::make('project.name')
                    ->label('Nama Project')
                    ->searchable(),
                TextColumn::make('statusrelation.name')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($record) => match($record->status) {
                        47 => 'warning',  // Menunggu Pembayaran
                        48 => 'info',     // Menunggu Konfirmasi
                        49 => 'success',  // Selesai
                        50 => 'danger',   // Ditolak
                        default => 'gray',
                    })
                    ->searchable(),
                TextColumn::make('total_amount')
                    ->label('Pembelian')
                    ->formatStateUsing(function ($state, $record) {
                        $percent = number_format($record->purchase_percentage, 0, ',', '.');
                        // Format angka dengan ribuan dan tanpa desimal, serta prefix 'Rp'
                        $formattedAmount = 'Rp ' . number_format($state, 0, ',', '.');
                        // Tambahkan span kecil hijau
                        return $formattedAmount . 
                            '   <span style="font-size:0.75em; color:green;">' . $percent . '%</span>';
                    })
                    ->html(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('detail')
                    ->label('')
                    ->icon('heroicon-o-chevron-right')
                    ->button()
                    ->color('primary')
                    ->modalHeading('Detail Transaksi')
                    ->modalWidth('7xl')
                    ->fillForm(fn ($record) => $record->toArray())
                    ->form(TransactionProjectDetailModal::getSchema())
                    ->modalFooterActions(TransactionProjectDetailModal::getActions())
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    // ->stickyModalHeader()
                    ->stickyModalFooter()
            ])
            ->bulkActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}