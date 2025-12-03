<?php
// app/Filament/Widgets/RecentSalesWidget.php
namespace App\Filament\Widgets;

use App\Models\Sale;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentSalesWidget extends BaseWidget
{
    protected static ?string $heading = 'Recent Sales';
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Sale::query()->latest()->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')
                    ->label('Invoice'),
                Tables\Columns\TextColumn::make('buyer.name')
                    ->label('Buyer'),
                Tables\Columns\TextColumn::make('sale_date')
                    ->date(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->money('IDR'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'draft',
                        'warning' => 'confirmed',
                        'success' => 'paid',
                        'danger' => 'cancelled',
                    ]),
            ]);
    }

    // public static function canView(): bool
    // {
    //     return auth()->user()->hasPermission('dashboard.view');
    // }
}