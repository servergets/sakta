<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Concerns\InteractsWithPageTable;

class ProjectOverviewWidget extends BaseWidget
{
    // use InteractsWithPageTable;

    // protected static bool $isDiscovered = false;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Project', '100')
                ->icon('heroicon-o-briefcase')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Project Aktif', '50')
                ->icon('heroicon-o-clipboard-document-check')
                ->color('success'),

            Stat::make('Total Pembelian', $this->formatCurrency(90000000))
                ->icon('heroicon-o-currency-dollar')
                ->color('primary'),

            Stat::make('Total Pembelian Terkumpul', $this->formatCurrency(15000000000))
                ->icon('heroicon-o-banknotes')
                ->color('success')
                ->extraAttributes(['class' => 'bg-white border border-gray-200 rounded-lg p-6']),
        ];
    }
    
    private function formatCurrency($amount): string
    {
        if ($amount >= 1000000000) {
            return 'Rp' . number_format($amount / 1000000000, 1) . ' M';
        }
        if ($amount >= 100000000) {
            return 'Rp' . number_format($amount / 1000000, 1) . ' Jt';
        }
        return 'Rp' . number_format($amount, 0, ',', '.');
    }
}
