<?php

namespace App\Filament\Resources\Products;

use App\Filament\Resources\Products\Pages\ListActiveProducts;
use App\Filament\Resources\Products\Tables\ProductsActiveTable;
use App\Models\Product;
use App\Models\Project;
use Filament\Support\Icons\Heroicon;
use Filament\Resources\Resource;
use UnitEnum;
use BackedEnum;
use Filament\Tables\Table;


class ActiveProductResource extends Resource
{
    protected static ?string $model = Project::class;
    
    protected static ?string $navigationLabel = 'Produk Aktif';
    protected static ?string $modelLabel = 'Produk Aktif';
    protected static ?string $pluralModelLabel = 'Produk Aktif';
    
    protected static string | UnitEnum | null $navigationGroup = 'Produk';
    // protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    
    public static function table(Table $table): Table
    {
        return ProductsActiveTable::configure($table);
    }
    // Sembunyikan dari navigation, karena sudah diatur manual di panel
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListActiveProducts::route('/'),
        ];
    }
    
    // public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    // {
    //     return parent::getEloquentQuery()->where('is_active', true);
    // }
}