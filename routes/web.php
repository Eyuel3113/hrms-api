<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/health', fn() => response('OK', 200));

// routes/web.php or routes/api.php
Route::get('/create-admin-force-2025', function () {
    \App\Models\User::updateOrCreate(
        ['email' => 'admin@hrm.com'],
        [
            'name' => 'System Admin',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
            'role' => 'admin'
        ]
    );
    return "ADMIN CREATED/UPDATED → Email: admin@hrm.com | Password: password123";
});
// In routes/web.php — add this single route (protected)
Route::get('/generate-docs', function () {
    if (app()->environment('production')) {
        \Knuckles\Scribe\Scribe::generate();
        return 'Documentation generated! Visit /docs';
    }
    return 'Only works on production';
})->name('scribe.generate');