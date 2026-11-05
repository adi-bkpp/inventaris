<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/halo', function () {
    return 'Halo dari Laravel!';
});

Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard');

Route::resource('category', CategoryController::class);
