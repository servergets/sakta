<?php

namespace App\Filament\Resources\Transactions\Pages;

use App\Filament\Resources\Transactions\MarginBuyerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Facades\FilamentView;
use Illuminate\Contracts\View\View;

class ListMarginBuyer extends ListRecords
{
    protected static string $resource = MarginBuyerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ];
    }
}