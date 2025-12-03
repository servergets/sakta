<?php

namespace App\Filament\Resources\Estimations\Pages;

use App\Filament\Resources\Estimations\EstimationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEstimations extends ListRecords
{
    protected static string $resource = EstimationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Estimasi')
                ->modalHeading('Tambah Estimasi')
                ->createAnother(false)
                ->modalWidth('sm')
                ->modalSubmitActionLabel('Simpan') 
                ->modalCancelActionLabel('Batal')
                ->modalCancelAction(fn ($action) => $action->color('danger')->outlined())
                ->modalFooterActionsAlignment('right'),
        ];
    }
}
