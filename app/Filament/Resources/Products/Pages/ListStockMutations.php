<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\StockMutationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;

class ListStockMutations extends ListRecords
{
    protected static string $resource = StockMutationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Pesan Produk')
                ->modalHeading('Pesan Produk')
                ->createAnother(false)
                ->modalSubmitActionLabel('Simpan') 
                ->modalCancelActionLabel('Batal')
                ->modalCancelAction(fn ($action) => $action->color('danger')->outlined())
                ->modalFooterActionsAlignment('right'),
        ];
    }
}
