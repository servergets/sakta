<?php

namespace App\Filament\Resources\Estimations\Pages;

use App\Filament\Resources\Estimations\EstimationResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewEstimation extends ViewRecord
{
    protected static string $resource = EstimationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
