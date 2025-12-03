<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\{Grid, Section, View, Wizard, Wizard\Step};
use Filament\Schemas\Schema;
use App\Models\MasterGlobal;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;
use Filament\Tables\Columns\TextColumn;




class DetailProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Progress Stepper (Read-only)
                // View::make('filament.resources.projects.progress-stepper')
                //     ->viewData(fn ($record) => [
                //         'currentStatus' => $record?->status,
                //         'statuses' => self::getProjectStatuses(),
                //     ])
                //     ->columnSpanFull(),
                
                Wizard::make([
                    Step::make('Belum dimulai')
                        ->icon('heroicon-o-clock'),
                    
                    Step::make('Pengiriman')
                        ->icon('heroicon-o-truck'),
                    
                    Step::make('Pemasaran')
                        ->icon('heroicon-o-megaphone'),
                    
                    Step::make('Selesai')
                        ->icon('heroicon-o-check-circle'),
                ])
                ->columnSpanFull()
                ->persistStepInQueryString()
                ->nextAction(fn ($action) => $action->disabled())
                ->previousAction(fn ($action) => $action->disabled())
                ->extraAttributes([
                    'class' => 'wizard-status-only',
                ])
                ->skippable()
                ->startOnStep(fn ($record) => self::getStepFromStatus($record?->status)),
                Grid::make(2)
                    ->schema([
                        self::getProjectInformationSection(),
                        self::getBuyerInformationSection(),
                    ])
                    ->columnSpanFull(),
                
                // Footer with Update Status Button
                // View::make('filament.resources.projects.update-status-footer')
                //     ->viewData(fn ($record) => [
                //         'record' => $record,
                //         'statuses' => self::getProjectStatuses(),
                //     ])
                //     ->columnSpanFull(),
            ]);
    }

    protected static function getProjectInformationSection(): Section
    {
        return Section::make('Informasi Project')
            ->icon('heroicon-o-information-circle')
            ->schema([
                Grid::make(2)
                    ->schema([
                        Placeholder::make('brand_logo')
                            ->hiddenLabel(true)
                            ->content(function ($record) {
                                if (! $record) {
                                    return new HtmlString('<div class="text-gray-400 italic">Tidak ada data profil.</div>');
                                }
                                // $record = $record->project;

                                $photoPath = $record->project_photo;

                                if ($photoPath) {
                                    $imageUrl = Storage::temporaryUrl(
                                        $photoPath,
                                        now()->addMinutes(5)
                                    );
                                } else {
                                    $imageUrl = asset('images/default-avatar.png');
                                }

                                return new HtmlString("
                                    <div class='fi-circular fi-ta-image'>
                                        <img src='{$imageUrl}' 
                                            alt='{$record->name}' style='height: 40px; width: 40px;'>
                                        <span>{$record->name}</span>
                                    </div>
                                ");
                            })
                            ->columnSpanFull(),
                        Placeholder::make('product.name')
                            ->label('Nama Produk')
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
                            ->html()
                            ->columns(1),
                        Placeholder::make('client.name')
                            ->label('Nama Klien')
                            ->formatStateUsing(function ($state, $record) {
                                $client = $record->client;
                            
                                if ($client && $client->image) {
                                    // Buat temporary signed URL
                                    $imageUrl = Storage::temporaryUrl(
                                        $client->image, 
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
                            ->html()
                            ->columns(1),
                        Placeholder::make('start_date')
                            ->label('Tanggal Mulai')
                            ->content(fn ($record) => e($record->start_date?->format('d/m/Y') ?? '-')),
                        Placeholder::make('end_date')
                            ->label('Project Berakhir')
                            ->content(function ($record) {
                                $start = $record->start_date;
                                $end   = $record->end_date;

                                if (! $start || ! $end) {
                                    return '-';
                                }

                                $months = $start->diffInMonths($end);
                                $endFormatted = '<span style="color:red;">' . $end->format('d/m/Y') . '</span>';

                                return $months . ' bulan ' . $endFormatted;
                            })
                            ->html(),
                        
                        Grid::make(3)
                            ->schema([
                                Placeholder::make('product_type')
                                    ->label('Jenis Product')
                                    ->content(fn ($record) => e($record->product->producttype->name ?? '-')),
                                Placeholder::make('product_total')
                                    ->label('Jumlah Produk')
                                    ->content(fn ($record) => e($record->qty ? number_format($record->qty, 0, ',', '.') : '-')),
                                Placeholder::make('purchase_total')
                                    ->label('Total Pembelian')
                                    ->content(fn ($record) => e($record->total_amount ?? '-'))
                                    ->money('IDR', locale: 'id', decimalPlaces: 0),
                            ])
                            ->columnSpanFull(),
                        Grid::make(3)
                        ->schema([
                            Placeholder::make('margin_sakta')
                                ->label('Margin Sakta')
                                ->content(fn ($record) => ($record?->margin_sakta ?? 0) . '%'),
                            
                            Placeholder::make('margin_sales')
                                ->label('Margin Seller')
                                ->content(fn ($record) => ($record?->margin_sales ?? 0) . '%'),
                            
                            Placeholder::make('margin_buyer')
                                ->label('Margin Buyer')
                                ->content(fn ($record) => ($record?->margin_buyer ?? 0) . '%'),
                        ])
                        ->columnSpanFull(),
                
                    ]),
                                // Ganti Section Progress dengan ini:
                Section::make('Progress Penjualan')
                    ->schema([
                        \Filament\Forms\Components\ViewField::make('progress_visual')
                            ->view('filament.forms.components.progress-stats')
                            ->viewData(function ($record) {
                                return ['record' => $record];
                            }),
                    ]),
            ]);
    }

    protected static function getBuyerInformationSection(): Section
    {
        return Section::make('Informasi Buyer')
            ->icon('heroicon-o-users')
            ->schema([
                View::make('filament.resources.projects.buyer-list')
                    ->viewData(function ($record) {
                        $transactions = $record?->transactions()
                            ->with(['buyer'])
                            ->get();
                        
                        $buyers = [];
                        $totalPurchase = $record?->total_purchase ?? 1;
                        
                        foreach ($transactions as $transaction) {
                            if (!$transaction->buyer) continue;
                            
                            $buyerId = $transaction->buyer->id;
                            if (!isset($buyers[$buyerId])) {
                                $buyers[$buyerId] = [
                                    'name' => $transaction->buyer->name,
                                    'total_amount' => 0,
                                    'percentage' => 0,
                                ];
                            }
                            
                            $buyers[$buyerId]['total_amount'] += $transaction->total_amount;
                        }
                        
                        foreach ($buyers as $key => $buyer) {
                            $buyers[$key]['percentage'] = $totalPurchase > 0 
                                ? round(($buyer['total_amount'] / $totalPurchase) * 100) 
                                : 0;
                        }
                        
                        return ['buyers' => array_values($buyers)];
                    }),
            ]);
    }

    protected static function getProjectStatuses(): array
    {
        return MasterGlobal::group('project_status')
            ->get()
            ->mapWithKeys(fn ($status) => [$status->id => $status->name])
            ->toArray();
    }
    
    protected static function getStepFromStatus(?int $status): int
    {
        return match($status) {
            51 => 1, // Belum dimulai
            52 => 2, // Pengiriman
            53 => 3, // Pemasaran
            54 => 4, // Selesai
            default => 1,
        };
    }
}