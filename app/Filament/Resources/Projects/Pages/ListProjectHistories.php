<?php

namespace App\Filament\Resources\Projects\Pages;

use App\Filament\Resources\Projects\ProjectHistoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;


class ListProjectHistories extends ListRecords
{
    protected static string $resource = ProjectHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make()
            //     ->label('Tambah Project')
            //     ->modalHeading('Tambah Project')
            //     ->createAnother(false)
            //     ->modalSubmitActionLabel('Simpan') 
            //     ->modalCancelActionLabel('Batal')
            //     ->modalCancelAction(fn ($action) => $action->color('danger')->outlined())
            //     ->modalFooterActionsAlignment('right'),
        ];
    }
    
    protected function getHeaderWidgets(): array
    {
        return [
            // \App\Filament\Widgets\ProjectOverviewWidget::class,
        ];
    }
}
