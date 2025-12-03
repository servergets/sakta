<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use App\Models\Sale;
use App\Models\Transaction;
use App\Models\Buyer;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Projects', Project::count())
                ->description('Active projects: ' . Project::where('status', 'active')->count())
                ->descriptionIcon('heroicon-m-folder')
                ->color('primary'),

            Stat::make('Total Sales', 'Rp ' . number_format(Sale::where('status', 'paid')->sum('total_amount'), 0, ',', '.'))
                ->description('This month: Rp ' . number_format(Sale::where('status', 'paid')->whereMonth('created_at', now()->month)->sum('total_amount'), 0, ',', '.'))
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),

            Stat::make('Total Transactions', Transaction::where('status', 'completed')->count())
                ->description('Pending: ' . Transaction::where('status', 'pending')->count())
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning'),

            Stat::make('Total Buyers', Buyer::count())
                ->description('New this month: ' . Buyer::whereMonth('created_at', now()->month)->count())
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),
        ];
    }

    // public static function canView(): bool
    // {
    //     return auth()->user()->hasPermission('dashboard.view');
    // }
}