<?php

namespace App\Filament\Resources\Buyers\Pages;

use App\Filament\Resources\Buyers\BuyerResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions;

class CreateBuyer extends CreateRecord
{
    protected static string $resource = BuyerResource::class;

    protected function getHeaderActions(): array
    { 
        return [
            Actions\CreateAction::make()
                ->visible(fn () => auth()->user()->hasPermission('buyer.create')),
        ];
    }
    
    // Optional: Tambahkan method lainnya
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
