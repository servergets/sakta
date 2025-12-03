<?php

namespace App\Filament\Resources\Projects\Pages;

use App\Filament\Resources\Projects\ProjectResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditProject extends EditRecord
{
    protected static string $resource = ProjectResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        return auth()->user()->hasPermission('project.edit');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()
                ->visible(fn () => auth()->user()->hasPermission('project.view')),
            Actions\DeleteAction::make()
                ->visible(fn () => auth()->user()->hasPermission('project.delete')),
        ];
    }
}
