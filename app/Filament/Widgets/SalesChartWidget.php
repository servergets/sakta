<?php


// app/Filament/Widgets/SalesChartWidget.php
namespace App\Filament\Widgets;

use App\Models\Sale;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class SalesChartWidget extends ChartWidget
{
    protected ?string $heading = 'Monthly Sales';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = [];
        $labels = [];

        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $labels[] = $month->format('M Y');
            $data[] = Sale::where('status', 'paid')
                ->whereYear('sale_date', $month->year)
                ->whereMonth('sale_date', $month->month)
                ->sum('total_amount');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Sales',
                    'data' => $data,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderColor' => 'rgb(59, 130, 246)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    // public static function canView(): bool
    // {
    //     return auth()->user()->hasPermission('dashboard.view');
    // }
}