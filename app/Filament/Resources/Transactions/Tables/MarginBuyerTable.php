<?php

namespace App\Filament\Resources\Transactions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;
// use App\Filament\Resources\Transactions\Schemas\DetailProjectForm;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;


class MarginBuyerTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->label('Nama Project'),
                TextColumn::make('dates')
                    ->label('Tanggal')
                    ->html()
                    ->getStateUsing(function ($record) {
                        $start = \Carbon\Carbon::parse($record->start_date)->format('d/m/Y');
                        $end = \Carbon\Carbon::parse($record->end_date)->format('d/m/Y');
                        return $start . '<br><span style="color: #ef4444;">' . $end . '</span>';
                    })
                    ->sortable(),
                TextColumn::make('buyer_count')
                    ->label('Buyer')
                    ->getStateUsing(function ($record) {
                        // Count buyer_id yang unique/distinct di project_transactions
                        return $record->transactions()->distinct('buyer_id')->count('buyer_id');
                    })
                    ->alignCenter()
                    ->sortable(),
                
                TextColumn::make('statusrelation.name')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($record) => match((int)$record->status) {
                        55 => 'warning',  // Menunggu Pembayaran
                        56 => 'success',   // Telah Dibayar
                        default => 'gray', //
                    })
                    ->searchable(),
                TextColumn::make('total_purchase')
                    ->label('Dibutuhkan')
                    ->formatStateUsing(function ($state, $record) {
                        $percent = number_format($record->purchase_percentage, 0, ',', '.');
                        // Format angka dengan ribuan dan tanpa desimal, serta prefix 'Rp'
                        $formattedAmount = 'Rp ' . number_format($state, 0, ',', '.');
                        // Tambahkan span kecil hijau
                        return $formattedAmount;
                    })
                    ->html(),
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
            ])
            ->filters([
                //
            ])
            ->recordActions([
                // Action::make('view_detail')
                //     ->icon('heroicon-o-arrow-right-circle')
                //     ->label('Detail')
                //     ->color('primary')
                //     ->modalHeading('Detail Project')
                //     ->form(function ($record) {
                //         $schema = DetailProjectForm::configure(Schema::make());
                //         return $schema->getComponents();
                //     })
                //     ->fillForm(function ($record) {
                //     //     // Hitung data yang diperlukan
                //     //     $soldQty = $record->sold_qty ?? 0;
                //     //     $remainingQty = $record->qty - $soldQty;
                //     //     $totalSales = $record->total_sales ?? 0;
                //     //     $totalMargin = $record->total_margin ?? 0;
                //     //     $client = $record->client;
                //     //     $brand = $record->product->brand ?? null;
                //     //     $product = $record->product;
                        
                //     //     return [
                //     //         'project_name' => $record->name,
                //     //         'start_date' => $record->start_date->format('d/m/Y'),
                //     //         'price' => $record->price,
                //     //         'qty' => $record->qty,
                //     //         'brand' => $brand->name ?? '-',
                //     //         'category' => $product->category ?? 'Face Wash',
                //     //         'client_name' => $client->company_name ?? $client->name,
                //     //         'client_phone' => $client->phone ?? '-',
                //     //         'client_email' => $client->email ?? '-',
                //     //         'estimation' => $record->start_date->diffInMonths($record->end_date ?? now()->addMonths(4)) . ' bulan (' . $record->start_date->format('d/m/Y') . ')',
                //     //         'sold_qty' => $soldQty,
                //     //         'remaining_qty' => $remainingQty,
                //     //         'budget' => $record->budget,
                //     //         'total_sales' => $totalSales,
                //     //         'total_margin' => $totalMargin,
                //     //     ];
                //     })
                //     ->modalWidth('10xl')
                //     // ->modalCancelAction(false)
                //     ->stickyModalFooter()
                //     ->extraModalFooterActions([
                //         Action::make('changeStatus')
                //             ->label('Perbarui Status')
                //             ->color('primary')
                //             ->modalHeading('Update Status Project')
                //             ->form([
                //                 \Filament\Forms\Components\Select::make('status')
                //                     ->label('Status Project')
                //                     ->options(
                //                         \App\Models\MasterGlobal::group('project_status')->pluck('name', 'id')
                //                     )
                //                     ->required(),
                //             ])
                //             ->action(function ($record, $data) {
                //                 $record->update([
                //                     'status' => $data['status'],
                //                 ]);

                //                 \Filament\Notifications\Notification::make()
                //                 ->title('Status berhasil diperbarui.')
                //                 ->success()
                //                 ->send();
                //             }),
                //     ])
                //     ->modalSubmitAction(false)
                //     ->modalFooterActionsAlignment('end'),
                // // ViewAction::make(),
                // EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
