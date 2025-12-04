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
