<?php

namespace App\Filament\Widgets;

use App\Models\Message;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class MessagesChart extends ChartWidget
{
    protected static ?string $heading = 'Messages reçus — 6 derniers mois';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $months = collect();
        $counts = collect();

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months->push($date->translatedFormat('M Y'));
            $counts->push(
                Message::whereYear('created_at', $date->year)
                       ->whereMonth('created_at', $date->month)
                       ->count()
            );
        }

        return [
            'datasets' => [
                [
                    'label'           => 'Messages',
                    'data'            => $counts->toArray(),
                    'backgroundColor' => 'rgba(99, 102, 241, 0.15)',
                    'borderColor'     => 'rgb(99, 102, 241)',
                    'borderWidth'     => 2,
                    'tension'         => 0.4,
                    'fill'            => true,
                    'pointBackgroundColor' => 'rgb(99, 102, 241)',
                ],
            ],
            'labels' => $months->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
