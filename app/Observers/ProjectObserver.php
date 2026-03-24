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
        Cache::forget('projects');
    }
}
