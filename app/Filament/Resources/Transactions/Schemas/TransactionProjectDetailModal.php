<?php

namespace App\Filament\Resources\Transactions\Schemas;

use Filament\Forms\Components\{
    Placeholder,
    FileUpload,
    TextInput,
    Select,
};
use Filament\Support\Enums\IconPosition;
use Illuminate\Support\HtmlString;
use Filament\Forms\Get;
use Filament\Schemas\Components\Grid;
use App\Models\MasterGlobal;
use Filament\Actions\Action as FormAction;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Facades\Storage;
use Filament\Tables\Columns\TextColumn;

class TransactionProjectDetailModal
{
    public static function getSchema(): array
    {
        return [
            Grid::make(2)
                ->schema([
                    Grid::make(1)
                        ->schema([
                    // ========== KOLOM KIRI: PROFIL BUYER ==========
                        Section::make()
                            ->schema([
                                Grid::make(['md' => 4,])
                                    ->schema([
                                    Placeholder::make('profile_photo')
                                        ->hiddenLabel(true)
                                        ->content(function ($record) {
                                            if (! $record) {
                                                return new HtmlString('<div class="text-gray-400 italic">Tidak ada data profil.</div>');
                                            }
                                            $record = $record->buyer;

                                            $photoPath = $record->profile_photo;

                                            if ($photoPath) {
                                                $imageUrl = Storage::temporaryUrl(
                                                    $photoPath,
                                                    now()->addMinutes(5)
                                                );
                                            } else {
                                                $imageUrl = asset('images/default-avatar.png');
                                            }

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
                                                ->content(fn ($record) => new \Illuminate\Support\HtmlString(
                                                    '<span class="font-bold text-base">' . e($record->buyer->buyer_name) . '</span>'
                                                )),
                                            Placeholder::make('id_number')
                                                ->label('No. KTP')
                                                ->content(fn ($record) => e($record->buyer->id_number ?? '-')),
                                            Placeholder::make('citizenship')
                                                ->label('Kewarganegaraan')
                                                ->content(fn ($record) => e($record->buyer->citizenship ?? '-')),
                                            Placeholder::make('tax_number')
                                                ->label('NPWP')
                                                ->content(fn ($record) => e($record->buyer->tax_number ?? '-')),
                                            Placeholder::make('birth_date')
                                                ->label('Tanggal Lahir')
                                                ->content(fn ($record) => $record->buyer->birth_date ? date('d/m/Y', strtotime($record->buyer->birth_date)) : '-'),
                                            Placeholder::make('phone')
                                                ->label('Nomor Telepon')
                                                ->content(fn ($record) => e($record->buyer->phone ?? '-')),
                                            Placeholder::make('gender')
                                                ->label('Jenis Kelamin')
                                                ->content(fn ($record) => e($record->buyer->gender->name ?? '-')),
                                            Placeholder::make('company_name')
                                                ->label('Nomor Kantor')
                                                ->content(fn ($record) => e($record->buyer->company_name ?? '-')),
                                            Placeholder::make('religion')
                                                ->label('Agama')
                                                ->content(fn ($record) => e($record->buyer->religion->name ?? '-')),
                                            Placeholder::make('email')
                                                ->label('Email')
                                                ->content(fn ($record) => e($record->buyer->email ?? '-')),
                                    ])
                                    ->columnSpan(3),
                            
                                ])
                            ])
                            ->columnSpan(1),
                        Section::make('Alamat Rumah')
                            ->schema([
                                Placeholder::make('home_address')
                                    ->label('Alamat Lengkap')
                                    ->content(fn ($record) => new \Illuminate\Support\HtmlString(
                                        '<div class="text-sm">' . e($record->buyer->home_address ?? '-') . '</div>'
                                    )),
                                Grid::make(2)
                                    ->schema([
                                        Placeholder::make('home_country')
                                            ->label('Negara')
                                            ->content(fn ($record) => e($record->buyer->homeCountry->name ?? '-')),
                                        Placeholder::make('home_province')
                                            ->label('Provinsi')
                                            ->content(fn ($record) => e($record->buyer->homeProvince->province_name ?? '-')),
                                        Placeholder::make('home_city')
                                            ->label('Kota')
                                            ->content(fn ($record) => e($record->buyer->homeCity->city_name ?? '-')),
                                        Placeholder::make('home_postal_code')
                                            ->label('Kode Pos')
                                            ->content(fn ($record) => e($record->buyer->home_postal_code ?? '-')),
                                    ]),
                            ]),
                    ]),
                    
                    // ========== KOLOM KANAN: DETAIL PROJECT & PEMBAYARAN ==========
                    Section::make()
                        ->schema([
                            Grid::make(1)
                            ->schema([
                                Placeholder::make('brand_logo')
                                    ->hiddenLabel(true)
                                    ->content(function ($record) {
                                        if (! $record) {
                                            return new HtmlString('<div class="text-gray-400 italic">Tidak ada data profil.</div>');
                                        }
                                        $record = $record->project;

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
                                    ->columnSpan(1),
                                
                                Placeholder::make('product_name')
                                    ->label('Nama Produk')
                                    ->content(fn ($record) => e($record->project->product->name ?? '-')),
                                Grid::make(2)
                                    ->schema([
                                        Placeholder::make('brand_name')
                                            ->label('Nama Brand')
                                            ->content(fn ($record) => e($record->project->brand->name ?? '-')),
                                        Placeholder::make('client_name')
                                            ->label('Klien')
                                            ->content(fn ($record) => e($record->project->client->name ?? '-')),
                                        Placeholder::make('start_date')
                                            ->label('Tanggal Mulai')
                                            ->content(fn ($record) => e($record->project->start_date?->format('d/m/Y') ?? '-')),
                                        Placeholder::make('end_date')
                                            ->label('Project Berakhir')
                                            ->content(function ($record) {
                                                $start = $record->project->start_date;
                                                $end   = $record->project->end_date;

                                                if (! $start || ! $end) {
                                                    return '-';
                                                }

                                                $months = $start->diffInMonths($end);
                                                $endFormatted = '<span style="color:red;">' . $end->format('d/m/Y') . '</span>';

                                                return $months . ' bulan ' . $endFormatted;
                                            })
                                            ->html(),
                                    ]),
                                Grid::make(3)
                                    ->schema([
                                        Placeholder::make('product_type')
                                            ->label('Jenis Product')
                                            ->content(fn ($record) => e($record->project->product->producttype->name ?? '-')),
                                        Placeholder::make('product_total')
                                            ->label('Total Produk')
                                            ->content(fn ($record) => e($record->project->qty ? number_format($record->project->qty, 0, ',', '.') : '-')),
                                        Placeholder::make('purchase_total')
                                            ->label('Total Pembelian')
                                            ->content(fn ($record) => e($record->total_amount ?? '-'))
                                            ->money('IDR', locale: 'id', decimalPlaces: 0),
                                    ]),
                                    
                                Placeholder::make('purchase_summary')
                                    ->label('')
                                    ->content(function ($record) {
                                        if (!$record) {
                                            return new HtmlString('');
                                        }

                                        $totalAmount = $record->total_amount_raw ? number_format($record->total_amount_raw, 0, ',', '.') : '0';
                                        $percentage = $record->purchase_percentage ?? 0;
                                        $estimation = $record->estimation ?? '-';
                                        $project = $record->project;
                                        $projectTotal = $project ? ($project->qty * $project->price) : 0;
                                        $projectTotalFormatted = number_format($projectTotal, 0, ',', '.');

                                        return new HtmlString("
                                            <div class='mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200'>
                                                <h4 class='text-sm font-semibold text-gray-900 mb-3'>Pembelian</h4>
                                                <div class='space-y-2'>
                                                    <div class='flex justify-between items-center'>
                                                        <span class='text-sm text-gray-600'>Presentase</span>
                                                        <strong class='text-sm text-amber-600'>{$percentage}%</strong>
                                                    </div>
                                                    <div class='flex justify-between items-center'>
                                                        <span class='text-sm text-gray-600'>Dari estimasi</span>
                                                        <strong class='text-sm text-green-600'>Rp{$projectTotalFormatted}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        ");
                                    }),

                                Placeholder::make('payment_details')
                                    ->label('')
                                    ->content(function ($record) {
                                        if (!$record) {
                                            return new HtmlString('');
                                        }

                                        $paymentMethod = $record->paymentMethod;
                                        $bankName = $paymentMethod?->bank_name ?? '-';
                                        $taxPercentage = $record->tax_percentage ?? 10;
                                        $paymentTotal = $record->payment_total ? number_format($record->payment_total, 0, ',', '.') : '0';

                                        return new HtmlString("
                                            <div class='mt-6 pt-4 border-t border-gray-200'>
                                                <h4 class='text-sm font-semibold text-gray-900 mb-4'>Detail Pembayaran</h4>
                                                <div class='space-y-3'>
                                                    <div class='grid grid-cols-2 gap-4'>
                                                        <div>
                                                            <span class='text-xs text-gray-500 block mb-1'>Metode Pembayaran</span>
                                                            <strong class='text-sm text-gray-900'>Transfer</strong>
                                                        </div>
                                                        <div>
                                                            <span class='text-xs text-gray-500 block mb-1'>Bank</span>
                                                            <strong class='text-sm text-gray-900'>{$bankName}</strong>
                                                        </div>
                                                    </div>
                                                    <div class='grid grid-cols-2 gap-4'>
                                                        <div>
                                                            <span class='text-xs text-gray-500 block mb-1'>Pajak</span>
                                                            <strong class='text-sm text-gray-900'>{$taxPercentage}%</strong>
                                                        </div>
                                                        <div>
                                                            <span class='text-xs text-gray-500 block mb-1'>Total Pembayaran</span>
                                                            <strong class='text-base text-red-600'>Rp{$paymentTotal}</strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        ");
                                    }),

                                // TAMPILKAN BUKTI PEMBAYARAN JIKA SUDAH ADA
                                FormAction::make('upload_payment')
                                    ->label('Unggah bukti pembayaran')
                                    ->icon('heroicon-o-arrow-up-tray')
                                    ->color('primary')
                                    ->modalHeading('Uplod Bukti Pembayaran')
                                    ->modalWidth('2xl')
                                    ->form([
                                        Grid::make(2)
                                            ->schema([
                                                TextInput::make('payment_method')
                                                    ->label('Metode Pembayaran')
                                                    ->default('Transfer')
                                                    ->disabled()
                                                    ->dehydrated(false),
                                                
                                                Select::make('payment_method_id')
                                                    ->label('Asal Bank')
                                                    ->options(function () {
                                                        return \App\Models\PaymentMethod::where('is_active', true)
                                                            ->pluck('bank_name', 'id');
                                                    })
                                                    ->default(fn ($record) => $record->payment_method_id ?? null)
                                                    ->searchable()
                                                    ->required()
                                                    ->reactive(),
                                                
                                                TextInput::make('buyer_name')
                                                    ->label('Nama Buyer')
                                                    ->default(fn ($record) => $record->buyer->buyer_name)
                                                    ->disabled()
                                                    ->dehydrated(false),
                                                
                                                TextInput::make('transfer_amount')
                                                    ->label('Jumlah Transfer')
                                                    ->numeric()
                                                    ->prefix('Rp')
                                                    ->default(fn ($record) => $record->payment_total ?? $record->total_amount_raw)
                                                    ->required()
                                                    ->live(onBlur: true)
                                                    ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.'))
                                                    // ->deformatStateUsing(fn ($state) => (int) preg_replace('/[^0-9]/', '', $state))
                                                    ,
                                            ]),
                                        
                                        FileUpload::make('payment_proof')
                                            ->label('Unggah Bukti Transfer')
                                            ->helperText('Format: JPG, PNG (Max: 2MB)')
                                            ->image()
                                            ->maxSize(2048)
                                            ->acceptedFileTypes(['image/jpeg', 'image/png'])
                                            ->disk('local')
                                            ->directory(function ($record) {
                                                if ($record && $record->id) {
                                                    return "transaction/project/{$record->id}";
                                                }
                                                return 'transaction/project/temp';
                                            })
                                            ->visibility('private')
                                            ->default(fn ($record) => $record?->payment_proof)
                                            ->reactive()
                                            ->required()
                                            // ->imagePreviewHeight('250')
                                            ->imageEditor()
                                            ->columnSpanFull(),
                                    ])
                                    ->modalSubmitActionLabel('Simpan')
                                    ->modalCancelActionLabel('Batal')
                                    ->visible(fn ($record) => in_array($record->status, [47, 48]))
                                    ->action(function ($record, array $data) {
                                        // Hapus file lama jika ada
                                        if ($record->payment_proof && Storage::disk('local')->exists($record->payment_proof)) {
                                            Storage::disk('local')->delete($record->payment_proof);
                                        }

                                        // Update dengan file baru
                                        $record->update([
                                            'payment_proof' => $data['payment_proof']
                                        ]);

                                        \Filament\Notifications\Notification::make()
                                            ->title('Bukti pembayaran berhasil diupload')
                                            ->success()
                                            ->send();
                                    }),
            
            
                                Placeholder::make('current_payment_proof')
                                    ->label('Bukti Pembayaran Saat Ini')
                                    ->content(function ($record) {
                                        if (!$record || !$record->payment_proof) {
                                            return new HtmlString('
                                                <div class="flex flex-col items-center justify-center p-8 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                                                    <svg class="w-16 h-16 text-cyan-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:40px">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum ada pembayaran</h3>
                                                    <p class="text-sm text-gray-600 text-center mb-4">
                                                        Buyer belum mengunggah bukti pembayaran. Anda dapat<br>
                                                        mengunggah bukti pembayaran secara manual.
                                                    </p>
                                                </div>
                                            ');
                                        }

                                        // Cek apakah file ada di storage
                                        if (Storage::disk('local')->exists($record->payment_proof)) {
                                            
                                            $photoPath = $record->payment_proof;

                                            if ($photoPath) {
                                                $imageUrl = Storage::temporaryUrl(
                                                    $photoPath,
                                                    now()->addMinutes(5)
                                                );
                                            } else {
                                                $imageUrl = asset('images/default-avatar.png');
                                            }
                                            
                                            return new HtmlString("
                                                <div class='mt-2 p-4 bg-white rounded-lg border border-gray-200'>
                                                    <img src='{$imageUrl}' 
                                                        class='rounded-lg border border-gray-200 w-full max-h-80 object-contain' 
                                                        alt='Bukti Pembayaran'>
                                                </div>
                                            ");
                                        }

                                        return new HtmlString('<span class="text-red-400">File tidak ditemukan di storage</span>');
                                    }),
                            
                            ]),
                        ])
                ]),
        ];
    }

    public static function getActions(): array
    {
        return [
            FormAction::make('delete')
                ->label('Hapus Transaksi')
                ->icon('heroicon-o-trash')
                ->color('gray')
                ->requiresConfirmation()
                ->modalHeading('Hapus Transaksi')
                ->modalDescription('Apakah Anda yakin ingin menghapus transaksi ini?')
                ->modalSubmitActionLabel('Ya, Hapus')
                ->action(function ($record) {
                    // Hapus file bukti pembayaran jika ada
                    if ($record->payment_proof && Storage::disk('local')->exists($record->payment_proof)) {
                        Storage::disk('local')->delete($record->payment_proof);
                    }
                    
                    $record->delete();
                }),

            FormAction::make('reject')
                ->label('Tolak Pembayaran')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Tolak Pembayaran')
                ->modalDescription('Apakah Anda yakin ingin menolak pembayaran ini?')
                ->modalSubmitActionLabel('Ya, Tolak')
                ->visible(fn ($record) => $record->status == 48)
                ->action(function ($record) {
                    $record->update(['status' => 50]);
                    
                    \Filament\Notifications\Notification::make()
                        ->title('Pembayaran ditolak')
                        ->danger()
                        ->send();
                }),

            FormAction::make('confirm')
                ->label('Konfirmasi Pembayaran')
                ->icon('heroicon-o-check-circle')
                ->color('primary')
                ->requiresConfirmation()
                ->modalHeading('Konfirmasi Pembayaran')
                ->modalDescription('Apakah Anda yakin ingin mengkonfirmasi pembayaran ini?')
                ->modalSubmitActionLabel('Ya, Konfirmasi')
                ->modalFooterActionsAlignment('right')
                ->visible(fn ($record) => $record->status == 47)
                ->action(function ($record) {
                    $record->update(['status' => 48]);
                    
                    \Filament\Notifications\Notification::make()
                        ->title('Pembayaran dikonfirmasi')
                        ->success()
                        ->send();
                }),
            
            // ACTION UPLOAD BUKTI PEMBAYARAN - DIPINDAHKAN KE SINI
            FormAction::make('accept_payment')
                ->label('Konfirmasi Pembayaran')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Terima Pembayaran')
                ->modalDescription('Apakah Anda yakin ingin terima pembayaran ini?')
                ->modalSubmitActionLabel('Ya, Konfirmasi')
                ->modalFooterActionsAlignment('right')
                ->visible(fn ($record) => $record->status == 48)
                ->action(function ($record) {
                    if (empty($record->payment_proof)) {
                        \Filament\Notifications\Notification::make()
                            ->title('Bukti pembayaran belum diupload')
                            ->warning()
                            ->send();
                        return;
                    }
                    
                    $record->update(['status' => 49]);
                    
                    \Filament\Notifications\Notification::make()
                        ->title('Pembayaran berhasil diterima')
                        ->success()
                        ->send();
                }),
        ];
    }
}