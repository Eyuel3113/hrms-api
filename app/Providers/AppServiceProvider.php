<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Training;           // ← ADD THIS
use App\Observers\TrainingObserver; // ← ADD THIS

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
        Training::observe(TrainingObserver::class);
    }
}