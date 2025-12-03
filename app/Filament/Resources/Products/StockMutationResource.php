<?php

namespace App\Filament\Resources\Products;

use App\Filament\Resources\Products\Pages\ListStockMutations;
use App\Models\ProductMutation;
use Filament\Support\Icons\Heroicon;
use Filament\Resources\Resource;
use UnitEnum;
use BackedEnum;

class StockMutationResource extends Resource
{
    protected static ?string $model = ProductMutation::class;
    
    protected static ?string $navigationLabel = 'Mutasi Stok';
    protected static ?string $modelLabel = 'Mutasi Stok';
    protected static ?string $pluralModelLabel = 'Mutasi Stok';
    
    protected static string | UnitEnum | null $navigationGroup = 'Produk';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStockMutations::route('/'),
        ];
    }
    
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->whereHas('stockMutations');
    }
}