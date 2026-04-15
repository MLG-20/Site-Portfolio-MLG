<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\MessagesChart;
use App\Filament\Widgets\ProjectViewsChart;
use App\Filament\Widgets\RecentMessages;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\TechByCategoryChart;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Actions\Action;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationLabel = 'Tableau de bord';
    protected static ?string $title = 'Tableau de bord';

    public function getHeaderActions(): array
    {
        return [
            Action::make('visit_portfolio')
                ->label('📌 Voir le Portfolio')
                ->url(url('/'))
                ->openUrlInNewTab()
                ->color('info')
                ->icon('heroicon-o-arrow-top-right-on-square'),
        ];
    }

    public function getWidgets(): array
    {
        return [
            StatsOverview::class,       // KPIs
            ProjectViewsChart::class,   // Bar : vues par projet     ← côte à côte
            MessagesChart::class,       // Line : messages / mois    ←
            TechByCategoryChart::class, // Doughnut : tech par catégorie (seul)
            RecentMessages::class,      // Tableau derniers messages
        ];
    }

    public function getColumns(): int | string | array
    {
        return 2;
    }
}
