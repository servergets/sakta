<?php

namespace App\Filament\Resources\Buyers\Pages;

use App\Filament\Resources\Buyers\BuyerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBuyer extends EditRecord
{
    
    protected static string $resource = BuyerResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        return auth()->user()->hasPermission('buyer.edit');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->visible(fn () => auth()->user()->hasPermission('buyer.delete')),
        ];
    }
}
