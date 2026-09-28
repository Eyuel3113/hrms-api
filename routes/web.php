<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/health', fn() => response('OK', 200));

Route::get('/docs', function () {
    return view('scribe.index');
});
Route::redirect('/api/docs', '/docs');
Route::redirect('/documentation', '/docs');

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
