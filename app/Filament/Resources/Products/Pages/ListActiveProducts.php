<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ActiveProductResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;

class ListActiveProducts extends ListRecords
{
    protected static string $resource = ActiveProductResource::class;

    // public function table(Table $table): Table
    // {
    //     return ProductsTable::configure($table);
    // }
}