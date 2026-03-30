<?php

namespace App\Filament\Widgets;

use App\Models\Experience;
use App\Models\Message;
use App\Models\Project;
use App\Models\Technology;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalViews     = Project::sum('views_count');
        $topProject     = Project::orderByDesc('views_count')->first();
        $unreadMessages = Message::whereNull('read_at')->count();
        $totalMessages  = Message::count();
        $totalProjects  = Project::count();
        $totalExp       = Experience::count();
        $activeTech     = Technology::where('is_active', true)->count();
        $messagesMonth  = Message::whereMonth('created_at', now()->month)
                                 ->whereYear('created_at', now()->year)
                                 ->count();

        return [
            Stat::make('Vues totales des projets', number_format($totalViews))
                ->description($topProject ? '🏆 ' . $topProject->title . ' · ' . $topProject->views_count . ' vues' : 'Aucun projet')
                ->descriptionIcon('heroicon-o-eye')
                ->color('primary'),

            Stat::make('Messages reçus', $totalMessages)
                ->description($unreadMessages . ' non lu' . ($unreadMessages > 1 ? 's' : ''))
                ->descriptionIcon('heroicon-o-envelope')
                ->color($unreadMessages > 0 ? 'danger' : 'success'),

            Stat::make('Messages ce mois', $messagesMonth)
                ->description(now()->translatedFormat('F Y'))
                ->descriptionIcon('heroicon-o-calendar-days')
                ->color('info'),

            Stat::make('Projets publiés', $totalProjects)
                ->description('dans le portfolio')
                ->descriptionIcon('heroicon-o-folder-open')
                ->color('warning'),

            Stat::make('Expériences', $totalExp)
                ->description('formations & postes')
                ->descriptionIcon('heroicon-o-briefcase')
                ->color('gray'),

            Stat::make('Technologies actives', $activeTech)
                ->description('section Technologies')
                ->descriptionIcon('heroicon-o-cpu-chip')
                ->color('success'),
        ];
    }
}
