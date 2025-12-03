<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Facades\Storage;
use Filament\Tables\Columns\ImageColumn;
use App\Filament\Resources\Products\Schemas\ProductActiveForm;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;





class ProductsActiveTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->label('No')
                    ->rowIndex(),
                TextColumn::make('product.name')
                    ->label('Nama Produk')
                    ->searchable(),
                TextColumn::make('product.name')
                    ->label('Nama Produk')
                    ->searchable()
                    ->formatStateUsing(function ($state, $record) {
                        $product = $record->product;
                       
                        if ($product && $product->image) {
                            // Buat temporary signed URL
                            $imageUrl = Storage::temporaryUrl(
                                $product->image, 
                                now()->addMinutes(5) // expires dalam 5 menit
                            );
                        } else {
                            $imageUrl = asset('storage/images/default-product.png');
                        }
                        
                        return "
                            <div class='fi-circular fi-ta-image'>
                                <img src='{$imageUrl}' 
                                    alt='{$state}' style='height: 40px; width: 40px;'>
                                <span>{$state}</span>
                            </div>
                        ";
                    })
                    ->html(),
                TextColumn::make('name')
                    ->label('Nama Project')
                    ->searchable(),
                TextColumn::make('qty')
                    ->label('Stok Awal'),
                TextColumn::make('qty1')
                    ->label('Terjual'),
                TextColumn::make('qty2')
                    ->label('Tersisa'),
                TextColumn::make('qty3')
                    ->label('Total Penjualan'),
            ])
            ->filters([
                //
            ])
            
            ->recordActions([
                Action::make('view_detail')
                    ->icon('heroicon-o-arrow-right-circle')
                    ->label('Detail')
                    ->color('primary')
                    ->modalHeading('Detail Produk')
                    ->form(function ($record) {
                        $schema = ProductActiveForm::configure(Schema::make());
                        return $schema->getComponents();
                    })
                    ->fillForm(function ($record) {
                        // Hitung data yang diperlukan
                        $soldQty = $record->sold_qty ?? 0;
                        $remainingQty = $record->qty - $soldQty;
                        $totalSales = $record->total_sales ?? 0;
                        $totalMargin = $record->total_margin ?? 0;
                        $client = $record->client;
                        $brand = $record->product->brand ?? null;
                        $product = $record->product;
                        
                        return [
                            'project_name' => $record->name,
                            'start_date' => $record->start_date->format('d/m/Y'),
                            'price' => $record->price,
                            'qty' => $record->qty,
                            'brand' => $brand->name ?? '-',
                            'category' => $product->category ?? 'Face Wash',
                            'client_name' => $client->company_name ?? $client->name,
                            'client_phone' => $client->phone ?? '-',
                            'client_email' => $client->email ?? '-',
                            'estimation' => $record->start_date->diffInMonths($record->end_date ?? now()->addMonths(4)) . ' bulan (' . $record->start_date->format('d/m/Y') . ')',
                            'sold_qty' => $soldQty,
                            'remaining_qty' => $remainingQty,
                            'budget' => $record->budget,
                            'total_sales' => $totalSales,
                            'total_margin' => $totalMargin,
                        ];
                    })
                    ->modalWidth('4xl')
                    ->modalCancelAction(false),
            ])
            ->toolbarActions([
                
            ]);
    }
}
