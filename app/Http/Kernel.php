<?php
'api' => [
    \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class, // only if SPA, optional for pure API
    'throttle:api',
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
],
protected $middleware = [
    // These middleware run on EVERY request
    \App\Http\Middleware\TrustProxies::class,
    \Illuminate\Http\Middleware\HandleCors::class,
    \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
    \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
    \Illuminate\Foundation\Http\Middleware\TransformsRequest::class,          // ← ADD THIS LINE
    \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class, // ← AND THIS ONE
];