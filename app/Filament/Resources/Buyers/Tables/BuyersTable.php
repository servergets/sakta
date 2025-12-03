<?php

namespace App\Filament\Resources\Buyers\Tables;

use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

class BuyersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('buyer_name')
                    ->label('Nama Buyer')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('phone')
                    ->label('Nomor Telepon')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('sales_count')
                    ->label('Project Dibeli')
                    ->counts('sales')
                    ->sortable(),
                TextColumn::make('sales_sum_total_price')
                    ->label('Total Pembelian')
                    // ->sum('sales', 'total_price')
                    ->money('IDR')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('detail')
                    ->label('Detail')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->button()
                    ->color('primary')
                    ->modalHeading('Detail Buyer')
                    ->modalWidth('7xl')
                    // ->slideOver()
                    ->fillForm(fn ($record) => $record->toArray())
                    ->form([
                        Tabs::make('DetailBuyer')
                            ->tabs([
                                // Tab 1: Informasi Buyer
                                Tabs\Tab::make('Informasi Buyer')
                                    ->icon('heroicon-o-user')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                // Kolom Kiri - Profil Buyer
                                                Grid::make(1)
                                                    ->schema([
                                                    Section::make('Profil Buyer')
                                                        ->schema([
                                                            Placeholder::make('profile_photo')
                                                                ->label('')
                                                                ->content(function ($record) {
                                                                    if (! $record) {
                                                                        return new HtmlString('<div class="text-gray-400 italic">Tidak ada data profil.</div>');
                                                                    }

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
                                                                }),
                                                            
                                                            Grid::make(2)
                                                                ->schema([
                                                                    Placeholder::make('buyer_name')
                                                                        ->label('Nama Buyer')
                                                                        ->content(fn ($record) => new \Illuminate\Support\HtmlString(
                                                                            '<span class="font-bold text-base">' . e($record->buyer_name) . '</span>'
                                                                        )),
                                                                    Placeholder::make('id_number')
                                                                        ->label('No. KTP')
                                                                        ->content(fn ($record) => e($record->id_number ?? '-')),
                                                                    Placeholder::make('citizenship')
                                                                        ->label('Kewarganegaraan')
                                                                        ->content(fn ($record) => e($record->citizenship ?? '-')),
                                                                    Placeholder::make('tax_number')
                                                                        ->label('NPWP')
                                                                        ->content(fn ($record) => e($record->tax_number ?? '-')),
                                                                    Placeholder::make('birth_date')
                                                                        ->label('Tanggal Lahir')
                                                                        ->content(fn ($record) => $record->birth_date ? date('d/m/Y', strtotime($record->birth_date)) : '-'),
                                                                    Placeholder::make('phone')
                                                                        ->label('Nomor Telepon')
                                                                        ->content(fn ($record) => e($record->phone ?? '-')),
                                                                    Placeholder::make('gender')
                                                                        ->label('Jenis Kelamin')
                                                                        ->content(fn ($record) => e($record->gender->name ?? '-')),
                                                                    Placeholder::make('company_name')
                                                                        ->label('Nomor Kantor')
                                                                        ->content(fn ($record) => e($record->company_name ?? '-')),
                                                                    Placeholder::make('religion')
                                                                        ->label('Agama')
                                                                        ->content(fn ($record) => e($record->religion->name ?? '-')),
                                                                    Placeholder::make('email')
                                                                        ->label('Email')
                                                                        ->content(fn ($record) => e($record->email ?? '-')),
                                                                ]),
                                                        ])
                                                        ->columnSpan(1),
                                                
                                                    Section::make('Rekening')
                                                        ->schema([
                                                            Grid::make(3)
                                                                ->schema([
                                                                    Placeholder::make('account_holder_name')
                                                                        ->label('Nama')
                                                                        ->content(fn ($record) => e($record->account_holder_name ?? '-')),
                                                                    Placeholder::make('bank')
                                                                        ->label('Bank')
                                                                        ->content(fn ($record) => e($record->bank->name ?? '-')),
                                                                    Placeholder::make('account_number')
                                                                        ->label('Nomor Rekening')
                                                                        ->content(fn ($record) => e($record->account_number ?? '-')),
                                                                ]),
                                                        ]),
                                                    ])
                                                ->columnSpan(1),
                                                // Kolom Kanan - Alamat
                                                Grid::make(1)
                                                    ->schema([
                                                        Section::make('Alamat Rumah')
                                                            ->schema([
                                                                Placeholder::make('home_address')
                                                                    ->label('Alamat Lengkap')
                                                                    ->content(fn ($record) => new \Illuminate\Support\HtmlString(
                                                                        '<div class="text-sm">' . e($record->home_address ?? '-') . '</div>'
                                                                    )),
                                                                Grid::make(2)
                                                                    ->schema([
                                                                        Placeholder::make('home_country')
                                                                            ->label('Negara')
                                                                            ->content(fn ($record) => e($record->homeCountry->name ?? '-')),
                                                                        Placeholder::make('home_province')
                                                                            ->label('Provinsi')
                                                                            ->content(fn ($record) => e($record->homeProvince->province_name ?? '-')),
                                                                        Placeholder::make('home_city')
                                                                            ->label('Kota')
                                                                            ->content(fn ($record) => e($record->homeCity->city_name ?? '-')),
                                                                        Placeholder::make('home_postal_code')
                                                                            ->label('Kode Pos')
                                                                            ->content(fn ($record) => e($record->home_postal_code ?? '-')),
                                                                    ]),
                                                            ]),
                                                        
                                                        Section::make('Alamat Kantor')
                                                            ->schema([
                                                                Placeholder::make('office_address')
                                                                    ->label('Alamat Lengkap')
                                                                    ->content(fn ($record) => new \Illuminate\Support\HtmlString(
                                                                        '<div class="text-sm">' . e($record->office_address ?? '-') . '</div>'
                                                                    )),
                                                                Grid::make(2)
                                                                    ->schema([
                                                                        Placeholder::make('office_country')
                                                                            ->label('Negara')
                                                                            ->content(fn ($record) => e($record->officeCountry->name ?? '-')),
                                                                        Placeholder::make('office_province')
                                                                            ->label('Provinsi')
                                                                            ->content(fn ($record) => e($record->officeProvince->province_name ?? '-')),
                                                                        Placeholder::make('office_city')
                                                                            ->label('Kota')
                                                                            ->content(fn ($record) => e($record->officeCity->city_name ?? '-')),
                                                                        Placeholder::make('office_postal_code')
                                                                            ->label('Kode Pos')
                                                                            ->content(fn ($record) => e($record->office_postal_code ?? '-')),
                                                                    ]),
                                                            ]),
                                                        
                                                    ])
                                                    ->columnSpan(1),
                                            ]),
                                    ]),
                                
                                // Tab 2: Project Aktif
                                Tabs\Tab::make('Project Aktif')
                                    ->icon('heroicon-o-building-office-2')
                                    ->schema([
                                        Placeholder::make('projects')
                                            ->label('')
                                            ->content(function ($record) {
                                                // Ambil sales dari relasi
                                                $sales = $record->sales()->with(['project', 'unit'])->get();
                                                
                                                if ($sales->isEmpty()) {
                                                    return new \Illuminate\Support\HtmlString('
                                                        <div class="text-center py-12">
                                                            '.FilamentIcon::resolve('heroicon-o-star', ['class' => 'w-6 h-6 text-gray-400 mx-auto']).'
                                                            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada project aktif</h3>
                                                            <p class="mt-1 text-sm text-gray-500">Buyer ini belum memiliki pembelian project.</p>
                                                        </div>
                                                    ');
                                                }
                                                
                                                $html = '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">';
                                                
                                                foreach ($sales as $sale) {
                                                    // Status badge color
                                                    $statusColor = match($sale->status ?? 'active') {
                                                        'completed' => 'bg-green-500',
                                                        'shipped' => 'bg-blue-500',
                                                        'paid' => 'bg-yellow-500',
                                                        default => 'bg-orange-500',
                                                    };
                                                    
                                                    $statusIcon = match($sale->status ?? 'active') {
                                                        'completed' => '✓',
                                                        'shipped' => '→',
                                                        'paid' => '💰',
                                                        default => '○',
                                                    };
                                                    
                                                    $projectName = $sale->project->name ?? 'Project';
                                                    $unitName = $sale->unit->name ?? '-';
                                                    $price = number_format($sale->total_price ?? 0, 0, ',', '.');
                                                    $discount = $sale->discount_percentage ?? 10;
                                                    $date = $sale->sale_date ? date('d/m/Y', strtotime($sale->sale_date)) : '-';
                                                    $totalQty = $sale->quantity ?? 0;
                                                    $soldQty = $sale->quantity_delivered ?? 0;
                                                    
                                                    // Progress percentage
                                                    $progress = $totalQty > 0 ? round(($soldQty / $totalQty) * 100) : 0;
                                                    
                                                    $html .= '
                                                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition-shadow duration-200 bg-white">
                                                        <div class="flex items-start justify-between mb-3">
                                                            <div class="flex-1">
                                                                <h3 class="font-bold text-sm text-gray-900 mb-1">' . e($projectName) . '</h3>
                                                                <p class="text-xs text-gray-500">' . e($unitName) . '</p>
                                                            </div>
                                                            <span class="flex items-center justify-center w-6 h-6 rounded-full text-white text-xs ' . $statusColor . '">
                                                                ' . $statusIcon . '
                                                            </span>
                                                        </div>
                                                        
                                                        <div class="space-y-2 mb-3">
                                                            <div class="flex items-center gap-2">
                                                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                                <span class="font-bold text-sm text-gray-900">Rp' . $price . '</span>
                                                                <span class="text-xs text-green-600 font-medium">/ ' . $discount . '%</span>
                                                            </div>
                                                            
                                                            <div class="flex items-center gap-2 text-xs text-gray-600">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                </svg>
                                                                <span>' . $date . '</span>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="grid grid-cols-2 gap-3 mb-3 p-3 bg-gray-50 rounded-lg">
                                                            <div>
                                                                <div class="text-xs text-gray-500 mb-1">Total Kuantiti</div>
                                                                <div class="font-bold text-sm text-gray-900">' . number_format($totalQty) . '</div>
                                                            </div>
                                                            <div>
                                                                <div class="text-xs text-gray-500 mb-1">Produk Terjual</div>
                                                                <div class="font-bold text-sm text-gray-900">' . number_format($soldQty) . '</div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div>
                                                            <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                                                                <div class="bg-orange-500 h-2 rounded-full transition-all duration-300" style="width: ' . $progress . '%"></div>
                                                            </div>
                                                            <div class="text-xs text-gray-600 flex items-center gap-1">
                                                                <span class="font-medium text-orange-600">' . $progress . '%</span>
                                                                <span>Dari estimasi</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    ';
                                                }
                                                
                                                $html .= '</div>';
                                                
                                                return new \Illuminate\Support\HtmlString($html);
                                            }),
                                    ]),
                            ])
                            ->contained(false)
                            ->persistTabInQueryString(),
                    ])
                    ->modalFooterActions([
                        DeleteAction::make('deleteBuyer')
                            ->label('Hapus Buyer')
                            ->icon('heroicon-o-trash')
                            ->color('danger')
                            ->button()
                            ->requiresConfirmation()
                            ->modalHeading('Hapus Buyer')
                            ->modalDescription('Apakah Anda yakin ingin menghapus buyer ini? Tindakan ini tidak dapat dibatalkan.')
                            ->modalSubmitActionLabel('Ya, Hapus')
                            ->modalCancelActionLabel('Batal')
                            ->action(fn ($record) => $record->delete())
                            ->successNotification(
                                \Filament\Notifications\Notification::make()
                                    ->success()
                                    ->title('Buyer berhasil dihapus')
                                    ->body('Data buyer telah dihapus dari sistem.')
                            ),

                        EditAction::make('editBuyer')
                            ->label('Edit Buyer')
                            ->icon('heroicon-o-pencil')
                            ->color(Color::Teal)
                            ->button()
                            ->modalHeading('Edit Buyer')
                            ->modalWidth('7xl')
                            ->modalSubmitActionLabel('Simpan')
                            ->modalCancelActionLabel('Batal'),
                    ])
                    ->modalFooterActionsAlignment('right')
                    ->stickyModalHeader()
                    ->stickyModalFooter()
                    ->modalCancelAction(false),
            ])
            ->bulkActions([
                // Bulk actions jika diperlukan
            ]);
    }
}