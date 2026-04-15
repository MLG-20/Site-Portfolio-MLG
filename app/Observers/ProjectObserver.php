<?php

namespace App\Observers;

use App\Models\Project;
use Illuminate\Support\Facades\Cache;

class ProjectObserver
{
    public function created(Project $project): void
    {
        $this->clearCache();
    }

    public function updated(Project $project): void
    {
        $this->clearCache();
    }

    public function deleted(Project $project): void
    {
        $this->clearCache();
    }

    public function restored(Project $project): void
    {
        $this->clearCache();
    }

    public function forceDeleted(Project $project): void
    {
        $this->clearCache();
    }

    private function clearCache(): void
    {
        // Vider le cache spécifique
        Cache::forget('projects');
        Cache::forget('projects_published');
        
        // Vider tous les caches lors de modifications en prod
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        
        // Optional: vider les views si besoin
        // \Illuminate\Support\Facades\Artisan::call('view:clear');
    }
}
