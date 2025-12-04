<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/health', fn() => response('OK', 200));
Route::get('/railway-seed-once-12345', function () {
    if (app()->environment('production')) {
        \Artisan::call('db:seed');
        return "DATABASE SEEDED SUCCESSFULLY – You can now login!";
    }
    return "Only works on Railway";
});
Route::get('/fix-admin-password-2025', function () {
    \App\Models\User::where('email', 'admin@hrms.com')
        ->update(['password' => bcrypt('password')]);
    return 'Admin password fixed! You can now login with password: password';
});


