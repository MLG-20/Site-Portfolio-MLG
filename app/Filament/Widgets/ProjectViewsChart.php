<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use Filament\Widgets\ChartWidget;

class ProjectViewsChart extends ChartWidget
{
    protected static ?string $heading = 'Vues par projet';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $projects = Project::orderByDesc('views_count')->get();

        return [
            'datasets' => [
                [
                    'label'           => 'Vues',
                    'data'            => $projects->pluck('views_count')->toArray(),
                    'backgroundColor' => [
                        'rgba(99, 102, 241, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(168, 85, 247, 0.8)',
                        'rgba(20, 184, 166, 0.8)',
                        'rgba(251, 146, 60, 0.8)',
                    ],
                    'borderRadius' => 6,
                ],
            ],
            'labels' => $projects->map(fn ($p) => \Illuminate\Support\Str::limit($p->title, 20))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'x' => [
                    'ticks' => [
                        'maxRotation' => 45,
                        'minRotation' => 45,
                        'font' => ['size' => 11],
                    ],
                ],
            ],
            'plugins' => [
                'legend' => ['display' => false],
            ],
        ];
    }
}
