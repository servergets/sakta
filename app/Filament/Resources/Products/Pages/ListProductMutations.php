<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductMutationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProductMutations extends ListRecords
{
    protected static string $resource = ProductMutationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Pesan Produk')
                ->modalHeading('Pesan Produk')
                ->modalWidth('8xl')
                ->createAnother(false)
                ->modalSubmitActionLabel('Simpan') 
                ->modalCancelActionLabel('Batal')
                ->modalCancelAction(fn ($action) => $action->color('danger')->outlined())
                ->modalFooterActionsAlignment('right'),
        ];
    }
}
