<?php

namespace App\Filament\Resources\Brands;

use App\Filament\Resources\Brands\Pages\CreateBrand;
use App\Filament\Resources\Brands\Pages\EditBrand;
use App\Filament\Resources\Brands\Pages\ListBrands;
use App\Filament\Resources\Brands\Schemas\BrandForm;
use App\Filament\Resources\Brands\Tables\BrandsTable;
use App\Models\Brand;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Filament\Clusters\Settings\SettingsCluster as ClustersSettings;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;
    protected static ?string $cluster = ClustersSettings::class;

    const Label = 'Brand';
    
    protected static ?string $pluralModelLabel = self::Label;
    protected static ?string $navigationLabel = self::Label; 
    protected static ?int $navigationSort = 50;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return BrandForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BrandsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBrands::route('/'),
            // 'create' => CreateBrand::route('/create'),
            // 'edit' => EditBrand::route('/{record}/edit'),
        ];
    }
}
