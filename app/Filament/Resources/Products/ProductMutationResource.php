<?php

namespace App\Filament\Resources\Products;

use App\Filament\Resources\Products\Pages\ListProductMutations;
use App\Filament\Resources\Products\Schemas\ProductMutationForm;
use App\Filament\Resources\Products\Tables\ProductMutationsTable;
use App\Models\ProductMutation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProductMutationResource extends Resource
{
    protected static ?string $model = ProductMutation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'reference_code';

    public static function form(Schema $schema): Schema
    {
        return ProductMutationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductMutationsTable::configure($table);
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
            'index' => ListProductMutations::route('/'),
        ];
    }
}
