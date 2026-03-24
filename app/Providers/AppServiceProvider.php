<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Experience;
use App\Models\PersonalInfo;
use App\Models\Project;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        PersonalInfo::observe(\App\Observers\PersonalInfoObserver::class);
        Project::observe(\App\Observers\ProjectObserver::class);
        Experience::observe(\App\Observers\ExperienceObserver::class);
    }
}
