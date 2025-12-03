<?php

namespace App\Filament\Resources\Projects\Pages;

use App\Filament\Resources\Projects\ProjectResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Notifications\Notification;

class ViewProject extends ViewRecord
{
    protected static string $resource = ProjectResource::class;

    /**
     * Method untuk update status project
     * Dipanggil dari blade view melalui Livewire
     */
    public function updateProjectStatus($statusId)
    {
        // Validate status exists in MasterGlobal
        $statusExists = \App\Models\MasterGlobal::where('id', $statusId)
            ->where('group', 'project_status')
            ->where('is_active', true)
            ->exists();

        if (!$statusExists) {
            Notification::make()
                ->title('Status tidak valid')
                ->danger()
                ->send();
            
            return;
        }

        // Update project status
        $this->record->update([
            'status' => $statusId
        ]);

        // Send success notification
        Notification::make()
            ->title('Status berhasil diperbarui')
            ->success()
            ->send();

        // Refresh record to get updated data
        $this->record = $this->record->fresh();
    }
}