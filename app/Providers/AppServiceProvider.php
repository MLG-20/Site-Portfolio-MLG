<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\PersonalInfo;

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
}
}
