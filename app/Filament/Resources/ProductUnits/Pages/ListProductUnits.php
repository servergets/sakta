<?php

namespace App\Filament\Resources\ProductUnits\Pages;

use App\Filament\Resources\ProductUnits\ProductUnitResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProductUnits extends ListRecords
{
    protected static string $resource = ProductUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Satuan Produk')
                ->modalHeading('Tambah Satuan Produk')
                ->createAnother(false)
                ->modalWidth('sm')
                ->modalSubmitActionLabel('Simpan') 
                ->modalCancelActionLabel('Batal')
                ->modalCancelAction(fn ($action) => $action->color('danger')->outlined())
                ->modalFooterActionsAlignment('right'),
        ];
    }
}
