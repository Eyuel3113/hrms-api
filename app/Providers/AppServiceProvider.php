<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Models\Training;
use App\Observers\TrainingObserver;

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

        Relation::morphMap([
            'Employee'   => \App\Models\Employee::class,
            'Project'    => \App\Models\Project::class,
            'Department' => \App\Models\Department::class,
            'Leave'      => \App\Models\Leave::class,
            'Candidate'  => \App\Models\Candidate::class,
            'User'       => \App\Models\User::class,
            'Job'        => \App\Models\Job::class,
            'Training'   => \App\Models\Training::class,
            'Shift'      => \App\Models\Shift::class,
        ]);
    }
}