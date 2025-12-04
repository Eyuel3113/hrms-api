<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->configureRateLimiting();

        // THIS IS THE MAGIC LINE THAT FIXES PUT/PATCH + form-data FOREVER
        Request::macro('all', function () {
            if (in_array($this->method(), ['PUT', 'PATCH', 'DELETE'])) {
                parse_str($this->server('QUERY_STRING', ''), $query);
                return array_merge(
                    $this->query->all(),
                    $this->request->all(),
                    $this->files->all()
                );
            }
            return $this->request->all() + $this->files->all();
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));
        });

        if ($this->app->environment('local')) {
            \Knuckles\Scribe\Scribe::generate();
        }
    }