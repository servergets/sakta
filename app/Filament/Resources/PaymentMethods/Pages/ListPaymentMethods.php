<?php

namespace App\Filament\Resources\PaymentMethods\Pages;

use App\Filament\Resources\PaymentMethods\PaymentMethodResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPaymentMethods extends ListRecords
{
    protected static string $resource = PaymentMethodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Bank')
                ->modalHeading('Tambah Akun Bank')
                ->createAnother(false)
                ->modalSubmitActionLabel('Simpan') 
                ->modalCancelActionLabel('Batal')
                ->modalCancelAction(fn ($action) => $action->color('danger')->outlined())
                ->modalFooterActionsAlignment('right'),
        ];
    }
}