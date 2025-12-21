<?php

namespace App\Observers;

use App\Models\PersonalInfo;

class PersonalInfoObserver
{
    /**
     * Handle the PersonalInfo "created" event.
     */
    public function created(PersonalInfo $personalInfo): void
    {
        $this->clearCache();
    }

    /**
     * Handle the PersonalInfo "updated" event.
     */
    public function updated(PersonalInfo $personalInfo): void
    {
        $this->clearCache();
    }

    /**
     * Handle the PersonalInfo "deleted" event.
     */
    public function deleted(PersonalInfo $personalInfo): void
    {
        $this->clearCache();
    }

    /**
     * Clear all related caches
     */
    private function clearCache(): void
    {
        \Cache::forget('personal_info');
        \Cache::forget('portfolio_data');
    }
}