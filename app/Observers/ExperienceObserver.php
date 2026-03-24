<?php

namespace App\Observers;

use App\Models\Experience;
use Illuminate\Support\Facades\Cache;

class ExperienceObserver
{
    public function created(Experience $experience): void
    {
        $this->clearCache();
    }

    public function updated(Experience $experience): void
    {
        $this->clearCache();
    }

    public function deleted(Experience $experience): void
    {
        $this->clearCache();
    }

    public function restored(Experience $experience): void
    {
        $this->clearCache();
    }

    public function forceDeleted(Experience $experience): void
    {
        $this->clearCache();
    }

    private function clearCache(): void
    {
        Cache::forget('experiences');
    }
}
