<?php

namespace App\Filament\Widgets;

use App\Models\Technology;
use Filament\Widgets\ChartWidget;

class TechByCategoryChart extends ChartWidget
{
    protected static ?string $heading = 'Technologies par catégorie';
    protected static ?int $sort = 4;

    protected function getData(): array
    {
        $grouped = Technology::where('is_active', true)
            ->get()
            ->groupBy('category')
            ->map->count();

        return [
            'datasets' => [
                [
                    'data'            => $grouped->values()->toArray(),
                    'backgroundColor' => [
                        'rgba(99, 102, 241, 0.85)',
                        'rgba(16, 185, 129, 0.85)',
                        'rgba(245, 158, 11, 0.85)',
                        'rgba(239, 68, 68, 0.85)',
                        'rgba(59, 130, 246, 0.85)',
                    ],
                    'hoverOffset' => 6,
                ],
            ],
            'labels' => $grouped->keys()->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
