<?php

namespace App\Filament\Resources\Buyers\Pages;

use App\Filament\Resources\Buyers\BuyerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;


class ListBuyers extends ListRecords
{
    protected static string $resource = BuyerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Buyer')
                ->modalHeading('Tambah Buyer')
                ->createAnother(false)
                ->modalWidth('7xl')
                ->modalSubmitActionLabel('Simpan') 
                ->modalCancelActionLabel('Batal')
                ->modalCancelAction(fn ($action) => $action->color('danger')->outlined())
                ->modalFooterActionsAlignment('right'),
        ];
    }
}
