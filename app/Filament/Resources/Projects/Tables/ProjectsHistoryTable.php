<?php

namespace App\Filament\Resources\Projects\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProjectsHistoryTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                return $query->where(function ($q) {
                    $q->where('status', 54)
                    ->orWhereRaw('total_purchase = (SELECT SUM(total_amount) FROM project_transactions WHERE project_transactions.project_id = projects.id)');
                });
            })
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Project')
                    ->searchable(),
                TextColumn::make('dates')
                    ->label('Tanggal')
                    ->html()
                    ->getStateUsing(function ($record) {
                        $start = \Carbon\Carbon::parse($record->start_date)->format('d/m/Y');
                        return $start;
                    })
                    ->sortable(),
                
                TextColumn::make('statusrelation.name')
                    ->label('Status')
                    ->badge()
                    ->badge()
                    ->color(fn ($record) => match((int)$record->status) {
                        51 => 'warning',  // Project Belum dimulai
                        52 => 'info',     // Pengiriman
                        53 => 'warning',  // Pemasaran
                        54 => 'success',   // Selesai
                        default => 'gray', //
                    })
                    ->searchable(),
                TextColumn::make('purchasing')
                    ->label('Pembelian')
                    ->getStateUsing(function ($record) {
                        // Sum total_amount dari relasi transactions
                        return $record->transactions()->sum('total_amount');
                    })
                    ->formatStateUsing(function ($state, $record) {
                        // Hitung persentase berdasarkan total_purchase di Project
                        $percent = 0;
                        if ($record->total_purchase > 0) {
                            $percent = ($state / $record->total_purchase) * 100;
                        }
                        $percentFormatted = number_format($percent, 0, ',', '.');
                        
                        // Format angka dengan ribuan dan tanpa desimal, serta prefix 'Rp'
                        $formattedAmount = 'Rp ' . number_format($state, 0, ',', '.');
                        
                        // Tambahkan span kecil hijau untuk persentase
                        return $formattedAmount . 
                            '   <span style="font-size:0.75em; color:green;">' . $percentFormatted . '%</span>';
                    })
                    ->html(),
                TextColumn::make('sale')
                    ->label('Pembelian')
                    ->getStateUsing(function ($record) {
                        // Sum total_amount dari relasi transactions
                        $formattedAmount = 'Rp ' . number_format($record->transactions()->sum('total_amount'), 0, ',', '.');
                        return $formattedAmount;
                    }),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                // EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
