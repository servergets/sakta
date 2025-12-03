<?php

namespace App\Filament\Resources\ProductTypes\Pages;

use App\Filament\Resources\ProductTypes\ProductTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProductTypes extends ListRecords
{
    protected static string $resource = ProductTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Jenis Produk')
                ->modalHeading('Tambah Jenis Produk')
                ->createAnother(false)
                ->modalWidth('sm')
                ->modalSubmitActionLabel('Simpan') 
                ->modalCancelActionLabel('Batal')
                ->modalCancelAction(fn ($action) => $action->color('danger')->outlined())
                ->modalFooterActionsAlignment('right'),
        ];
    }
}
