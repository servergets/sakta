<?php
// app/Filament/Pages/Dashboard.php (Override default dashboard)
namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    // public static function canAccess(array $parameters = []): bool
    // {
    //     return auth()->user()->hasPermission('dashboard.view');
    // }

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\DashboardStatsWidget::class,
            \App\Filament\Widgets\SalesChartWidget::class,
            \App\Filament\Widgets\RecentSalesWidget::class,
            \App\Filament\Widgets\ProjectStatusWidget::class,
        ];
    }

    public function getColumns(): array|int
    {
        return 2; // atau return [1, 2, 3] untuk array
    }
}