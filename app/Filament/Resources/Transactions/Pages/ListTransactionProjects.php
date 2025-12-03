<?php

namespace App\Filament\Resources\Transactions\Pages;

use App\Filament\Resources\Transactions\TransactionProjectResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Facades\FilamentView;
use Illuminate\Contracts\View\View;

class ListTransactionProjects extends ListRecords
{
    protected static string $resource = TransactionProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Transaksi Project')
                ->modalHeading('Tambah Transaksi')
                ->createAnother(false)
                ->modalWidth('5xl')
                ->modalCancelAction(fn ($action) => $action->label('Batal')->color('danger')->outlined())
                ->modalSubmitAction(fn ($action) => $action
                    ->label('Simpan')
                    ->extraAttributes([
                        'style' => 'display: none; visibility: hidden;',
                        // 'class' => 'wizard-submit-btn'
                        'x-init' => '
                                $watch("$wire.mountedActionsData[0].step", (step) => {
                                    console.log("Current step:", step);
                                    const submitBtn = $el.closest("footer").querySelector("button[type=submit]");
                                    if (submitBtn) {
                                        if (step === 2 || step === "konfirmasi") {
                                            submitBtn.style.display = "inline-flex";
                                            submitBtn.style.visibility = "visible";
                                        } else {
                                            submitBtn.style.display = "none";
                                        }
                                    }
                                })
                            '
                    ])
                )
                ->modalFooterActionsAlignment('right')
                ->successNotificationTitle('Transaksi berhasil ditambahkan'),
        ];
    }
}