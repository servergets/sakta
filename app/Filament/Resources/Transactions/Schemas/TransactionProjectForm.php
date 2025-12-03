<?php

namespace App\Filament\Resources\Transactions\Schemas;

use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Forms\Components\{Select, TextInput, DatePicker, Placeholder, Hidden};
use App\Models\Buyer;
use App\Models\Project;
use App\Models\ProjectTransaction;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Storage;


class TransactionProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Wizard::make([
                    // === STEP 1: PILIH BUYER ===
                    Step::make('Pilih Buyer')
                        ->schema([
                            Select::make('buyer_id')
                                ->label('Pilih Buyer')
                                ->relationship('buyer', 'buyer_name')
                                ->searchable(['buyer_name', 'email', 'phone'])
                                ->preload()
                                ->required()
                                ->live()
                                ->afterStateUpdated(function ($state, $set) {
                                    $set('buyer_preview', null);
                                    
                                    if ($state) {
                                        $buyer = Buyer::with(['gender', 'religion'])->find($state);
                                        if ($buyer) {
                                            $buyerData = [
                                                'buyer_name' => $buyer->buyer_name ?? '-',
                                                'citizenship' => $buyer->citizenship ?? '-',
                                                'birth_date' => $buyer->birth_date ? $buyer->birth_date->format('d/m/Y') : '-',
                                                'gender' => $buyer->gender?->name ?? '-',
                                                'religion' => $buyer->religion?->name ?? '-',
                                                'id_number' => $buyer->id_number ?? '-',
                                                'tax_number' => $buyer->tax_number ?? '-',
                                                'phone' => $buyer->phone ?? '-',
                                                'company_name' => $buyer->company_name ?? '-',
                                                'email' => $buyer->email ?? '-',
                                                'profile_photo' => $buyer->profile_photo ?? '-',
                                            ];
                                            
                                            $set('buyer_preview', json_encode($buyerData));
                                        }
                                    }
                                }),

                            Hidden::make('buyer_preview')
                                ->default(null)
                                ->dehydrated(false),

                            Fieldset::make('Profil Buyer')
                                ->schema([
                                    Grid::make(['md' => 4,])
                                        ->schema([
                                        Placeholder::make('profile_photo')
                                            ->hiddenLabel(true)
                                            ->content(function ($get) {
                                                $preview = $get('buyer_preview');
                                                if (! $preview) {
                                                    return new HtmlString('<div class="text-gray-400 italic">Tidak ada data profil.</div>');
                                                }

                                                $record = (object)json_decode($preview, true);

                                                // Dapatkan path foto profil dari model
                                                $photoPath = $record->profile_photo;

                                                // Cek apakah ada foto dan buat temporary URL jika ada
                                                if ($photoPath) {
                                                    $imageUrl = Storage::temporaryUrl(
                                                        $photoPath,
                                                        now()->addMinutes(5) // URL berlaku 5 menit
                                                    );
                                                } else {
                                                    $imageUrl = asset('images/default-avatar.png');
                                                }

                                                // Kembalikan tampilan HTML-nya
                                                return new HtmlString('
                                                    <div class="flex justify-center mb-4">
                                                        <img src="' . $imageUrl . '" 
                                                            class="rounded-full object-cover border-4 border-gray-200 shadow-sm" 
                                                            alt="Profile Photo" style="height: 60px; width: 60px;">
                                                    </div>
                                                ');
                                            })
                                            ->columnSpan(1),
                                        
                                        Grid::make(2)
                                        ->schema([
                                            Placeholder::make('buyer_name')
                                                ->label('Nama Buyer')
                                                ->content(function ($get) {
                                                    $buyerPreview = json_decode($get('buyer_preview'), true) ?? [];
                                                    $buyerName = $buyerPreview['buyer_name'] ?? '-';
                                                    return new \Illuminate\Support\HtmlString(
                                                        '<span class="font-bold text-base">' . e($buyerName) . '</span>'
                                                    );
                                                }),
                                            Placeholder::make('id_number')
                                                ->label('No. KTP')
                                                ->content(function ($get) {
                                                    $buyerPreview = json_decode($get('buyer_preview'), true) ?? [];
                                                    return e($buyerPreview['id_number'] ?? '-');
                                                }),
                                            Placeholder::make('citizenship')
                                                ->label('Kewarganegaraan')
                                                ->content(function ($get) {
                                                    $buyerPreview = json_decode($get('buyer_preview'), true) ?? [];
                                                    return e($buyerPreview['citizenship'] ?? '-');
                                                }),
                                            Placeholder::make('tax_number')
                                                ->label('NPWP')
                                                ->content(function ($get) {
                                                    $buyerPreview = json_decode($get('buyer_preview'), true) ?? [];
                                                    return e($buyerPreview['tax_number'] ?? '-');
                                                }),
                                            Placeholder::make('birth_date')
                                                ->label('Tanggal Lahir')
                                                ->content(function ($get) {
                                                    $buyerPreview = json_decode($get('buyer_preview'), true) ?? [];
                                                    $birthDate = $buyerPreview['birth_date'] ?? null;
                                                    return $birthDate && $birthDate !== '-' ? date('d/m/Y', strtotime($birthDate)) : '-';
                                                }),
                                            Placeholder::make('phone')
                                                ->label('Nomor Telepon')
                                                ->content(function ($get) {
                                                    $buyerPreview = json_decode($get('buyer_preview'), true) ?? [];
                                                    return e($buyerPreview['phone'] ?? '-');
                                                }),
                                            Placeholder::make('gender')
                                                ->label('Jenis Kelamin')
                                                ->content(function ($get) {
                                                    $buyerPreview = json_decode($get('buyer_preview'), true) ?? [];
                                                    return e($buyerPreview['gender_name'] ?? '-');
                                                }),
                                            Placeholder::make('company_name')
                                                ->label('Nama Perusahaan') // Diperbaiki labelnya
                                                ->content(function ($get) {
                                                    $buyerPreview = json_decode($get('buyer_preview'), true) ?? [];
                                                    return e($buyerPreview['company_name'] ?? '-');
                                                }),
                                            Placeholder::make('religion')
                                                ->label('Agama')
                                                ->content(function ($get) {
                                                    $buyerPreview = json_decode($get('buyer_preview'), true) ?? [];
                                                    return e($buyerPreview['religion_name'] ?? '-');
                                                }),
                                            Placeholder::make('email')
                                                ->label('Email')
                                                ->content(function ($get) {
                                                    $buyerPreview = json_decode($get('buyer_preview'), true) ?? [];
                                                    return e($buyerPreview['email'] ?? '-');
                                                }),
                                        ])
                                        ->columnSpan(3),
                                    ]),
                                ])
                                ->visible(fn ($get) => filled($get('buyer_preview'))),
                        ]),

                    // === STEP 2: DETAIL TRANSAKSI ===
                    Step::make('Data Transaksi')
                        ->schema([
                            Select::make('project_id')
                                ->label('Pilih Project')
                                ->relationship('project', 'name')
                                ->searchable(['name'])
                                ->preload()
                                ->required()
                                ->live()
                                ->afterStateUpdated(function ($state, $set) {
                                    // Reset semua field
                                    $set('client_display_value', '-');
                                    $set('product_display_value', '-');
                                    $set('start_date', null);
                                    $set('estimation', null);
                                    $set('product_type', null);
                                    $set('product_qty', null);
                                    $set('project_total_value', null);
                                    $set('min_percentage', null);
                                    $set('available_percentage', null);
                                    $set('purchase_percentage', null);
                                    $set('total_amount', null);
                                    
                                    if ($state) {
                                        $project = Project::with(['client', 'product.productType'])->find($state);
                                        
                                        if ($project) {
                                            // Display values
                                            $set('client_display_value', $project->client?->name ?? '-');
                                            $set('product_display_value', $project->product?->name ?? '-');
                                            
                                            // Auto-fill: Tanggal Mulai
                                            if ($project->start_date) {
                                                $set('start_date', $project->start_date->format('Y-m-d'));
                                            }
                                            
                                            // Auto-fill: Estimasi (dalam bulan)
                                            if ($project->start_date && $project->end_date) {
                                                $months = $project->start_date->diffInMonths($project->end_date);
                                                $set('estimation', $months . ' Bulan');
                                            }
                                            
                                            // Auto-fill: Jenis Produk
                                            if ($project->product && $project->product->productType) {
                                                $set('product_type', $project->product->productType->name);
                                            }
                                            
                                            // Auto-fill: Jumlah Produk
                                            if ($project->qty) {
                                                $set('product_qty', number_format($project->qty, 0, ',', '.'));
                                            }
                                            
                                            // Calculate Total Project (qty × price)
                                            $projectTotal = $project->qty * $project->price;
                                            $set('project_total_value', $projectTotal);
                                            
                                            // Set minimum percentage (FIXED: jangan format sebagai string)
                                            $set('min_percentage', $project->min_purchase ?? 0);
                                            
                                            // Calculate available percentage
                                            $usedPercentage = ProjectTransaction::where('project_id', $state)
                                                ->whereNull('deleted_at')
                                                ->sum('purchase_percentage');
                                            
                                            $availablePercentage = 100 - $usedPercentage;
                                            $set('available_percentage', $availablePercentage);
                                        }
                                    }
                                }),

                            Hidden::make('client_display_value')->default('-')->dehydrated(false),
                            Hidden::make('product_display_value')->default('-')->dehydrated(false),
                            Hidden::make('project_total_value')->default(0)->dehydrated(false),
                            Hidden::make('min_percentage')->default(0)->dehydrated(false),
                            Hidden::make('available_percentage')->default(100)->dehydrated(false),

                            Grid::make(2)
                                ->schema([
                                    Placeholder::make('client_display')
                                        ->label('Klien')
                                        ->content(fn ($get) => $get('client_display_value') ?? '-'),

                                    Placeholder::make('product_display')
                                        ->label('Produk')
                                        ->content(fn ($get) => $get('product_display_value') ?? '-'),
                                ]),

                            Grid::make(2)
                                ->schema([
                                    DatePicker::make('start_date')
                                        ->label('Tanggal Mulai')
                                        ->required()
                                        ->native(false)
                                        ->displayFormat('d F Y')
                                        ->disabled()
                                        ->dehydrated(),

                                    TextInput::make('estimation')
                                        ->label('Estimasi')
                                        ->disabled()
                                        ->dehydrated(),
                                ]),

                            Grid::make(2)
                                ->schema([
                                    TextInput::make('product_type')
                                        ->label('Jenis Produk')
                                        ->disabled()
                                        ->dehydrated(false),

                                    TextInput::make('product_qty')
                                        ->label('Jumlah Produk')
                                        ->disabled()
                                        ->dehydrated(false),
                                ]),

                            Placeholder::make('available_info')
                                ->label('')
                                ->content(function ($get) {
                                    $available = $get('available_percentage');
                                    $min = $get('available_percentage') < $get('min_percentage') ? $get('available_percentage') : $get('min_percentage');
                                    
                                    if ($available === null) {
                                        return new HtmlString('');
                                    }
                                    
                                    if ($available <= 0) {
                                        return new HtmlString('
                                            <div class="flex items-start gap-3 p-4 mb-4 rounded-lg border-l-4" style="background-color: #fef2f2; border-color: #ef4444;">
                                                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" style="color: #dc2626;width:5%;" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                </svg>
                                                <div class="flex-1">
                                                    <p class="font-semibold text-sm mb-1" style="color: #991b1b;">Project Sudah Penuh</p>
                                                    <p class="text-sm" style="color: #b91c1c;">Project ini sudah mencapai 100% pembelian dan tidak dapat menerima transaksi baru.</p>
                                                </div>
                                            </div>
                                        ');
                                    }
                                    
                                    // Determine colors based on available percentage
                                    if ($available >= 50) {
                                        $bgColor = '#f0fdf4';
                                        $borderColor = '#22c55e';
                                        $textColor = '#166534';
                                        $lightTextColor = '#15803d';
                                        $statusText = 'Kuota Tersedia';
                                    } elseif ($available >= 20) {
                                        $bgColor = '#fefce8';
                                        $borderColor = '#eab308';
                                        $textColor = '#854d0e';
                                        $lightTextColor = '#a16207';
                                        $statusText = 'Kuota Terbatas';
                                    } else {
                                        $bgColor = '#fff7ed';
                                        $borderColor = '#f97316';
                                        $textColor = '#9a3412';
                                        $lightTextColor = '#c2410c';
                                        $statusText = 'Kuota Hampir Habis';
                                    }
                                    
                                    return new HtmlString("
                                        <div class='flex items-start gap-3 p-4 mb-4 rounded-lg border-l-4' style='background-color: {$bgColor}; border-color: {$borderColor};'>
                                            <svg class='w-5 h-5 flex-shrink-0 mt-0.5' style='color: {$borderColor};width:5%;' fill='currentColor' viewBox='0 0 20 20'>
                                                <path fill-rule='evenodd' d='M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z' clip-rule='evenodd'/>
                                            </svg>
                                            <div class='flex-1'>
                                                <p class='font-semibold text-sm mb-2' style='color: {$textColor};'>{$statusText}</p>
                                                <div class='space-y-1'>
                                                    <p class='text-sm' style='color: {$textColor};'><strong>Sisa Kuota:</strong> {$available}%</p>
                                                    <p class='text-sm' style='color: {$lightTextColor};'><strong>Minimum Pembelian:</strong> {$min}%</p>
                                                </div>
                                            </div>
                                        </div>
                                    ");
                                })
                                ->visible(fn ($get) => $get('project_id') !== null),

                            Grid::make(2)
                                ->schema([
                                    TextInput::make('purchase_percentage')
                                        ->label('Presentase Pembelian (%)')
                                        ->numeric()
                                        ->suffix('%')
                                        ->required()
                                        ->minValue(fn ($get) => ($get('available_percentage') < $get('min_percentage') ? $get('available_percentage') : $get('min_percentage')))
                                        ->maxValue(fn ($get) => $get('available_percentage') ?? 100)
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(function ($state, $get, $set) {
                                            if ($state && $get('project_total_value')) {
                                                $projectTotal = $get('project_total_value');
                                                $buyerTotal = ($state / 100) * $projectTotal;
                                                $set('total_amount', number_format($buyerTotal, 0, ',', '.'));
                                            } else {
                                                $set('total_amount', null);
                                            }
                                        })
                                        ->helperText(fn ($get) => 'Min: ' . ($get('available_percentage') < $get('min_percentage') ? $get('available_percentage') : $get('min_percentage')) . '% | Max: ' . ($get('available_percentage') ?? 100) . '%')
                                        ->disabled(fn ($get) => ($get('available_percentage') ?? 100) <= 0),

                                    TextInput::make('total_amount')
                                        ->label('Total Pembelian Buyer')
                                        ->prefix('Rp')
                                        ->required()
                                        ->disabled()
                                        ->dehydrated()
                                        ->helperText(fn ($get) => 'Total Project: Rp ' . number_format($get('project_total_value') ?? 0, 0, ',', '.')),
                                ]),
                        ]),

                    // === STEP 3: KONFIRMASI ===
                    Step::make('Konfirmasi')
                        ->schema([
                            Placeholder::make('confirmation_message')
                                    ->content(new HtmlString('
                                        <div style="text-align: center; padding: 1.5rem 0;">
                                            <div style="margin: 0 auto 1rem auto; display: flex; align-items: center; justify-content: center; width: 60px; height: 60px; border-radius: 50%; background-color: #dcfce7;">
                                                <svg style="width: 50%; height: 50%; color: #16a34a;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <p style="font-size: 1.25rem; font-weight: 600; color: #1f2937; margin-bottom: 0.5rem;">Review Data Transaksi</p>
                                            <p style="color: #6b7280;">Pastikan semua data sudah benar sebelum menyimpan transaksi.</p>
                                        </div>
                                    '))
                                ->label('')
                                ->extraAttributes(['class' => 'text-center']),
                        ]),
                ])
                ->skippable(false)
                ->persistStepInQueryString()              
                ->submitAction(new HtmlString('
                    <button type="submit" class="fi-color fi-color-primary fi-bg-color-400 hover:fi-bg-color-300 dark:fi-bg-color-600 dark:hover:fi-bg-color-700 fi-text-color-900 hover:fi-text-color-800 dark:fi-text-color-0 dark:hover:fi-text-color-0 fi-btn fi-size-md  fi-ac-btn-action"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7 21H17C19.2091 21 21 19.2091 21 17V7.41421C21 7.149 20.8946 6.89464 20.7071 6.70711L17.2929 3.29289C17.1054 3.10536 16.851 3 16.5858 3H7C4.79086 3 3 4.79086 3 7V17C3 19.2091 4.79086 21 7 21Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M9 3H15V6C15 6.55228 14.5523 7 14 7H10C9.44772 7 9 6.55228 9 6V3Z" stroke="black" stroke-width="2"/>
                        <path d="M17 21V14C17 13.4477 16.5523 13 16 13H8C7.44772 13 7 13.4477 7 14V21" stroke="black" stroke-width="2"/>
                        <path d="M11 17H13" stroke="black" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        Simpan Transaksi
                    </button>
                '))
                ->columnSpanFull()
            ]);
    }
}