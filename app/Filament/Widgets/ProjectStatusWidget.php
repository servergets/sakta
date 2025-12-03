<?php

// app/Filament/Widgets/ProjectStatusWidget.php
namespace App\Filament\Widgets;

use App\Models\Project;
use Filament\Widgets\ChartWidget;

class ProjectStatusWidget extends ChartWidget
{
    protected ?string $heading = 'Project Status Distribution';
    protected static ?int $sort = 4;

    protected function getData(): array
    {
        $statuses = [
            'planning' => Project::where('status', 'planning')->count(),
            'active' => Project::where('status', 'active')->count(),
            'completed' => Project::where('status', 'completed')->count(),
            'cancelled' => Project::where('status', 'cancelled')->count(),
        ];

        return [
            'datasets' => [
                [
                    'data' => array_values($statuses),
                    'backgroundColor' => [
                        '#6B7280', // planning - gray
                        '#3B82F6', // active - blue
                        '#10B981', // completed - green
                        '#EF4444', // cancelled - red
                    ],
                ],
            ],
            'labels' => array_keys($statuses),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    // public static function canView(): bool
    // {
    //     return auth()->user()->hasPermission('dashboard.view');
    // }
}