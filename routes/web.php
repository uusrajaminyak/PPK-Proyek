<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\FacilityController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Admin Module Routes (FR-13, FR-14, FR-15, FR-16, FR-17)
|--------------------------------------------------------------------------
| Direct access to Admin features without auth middleware dependencies.
*/
Route::prefix('admin')->name('admin.')->group(function () {
    
    // 1. Dashboard & Rekap (FR-17)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // 2. Kelola Data Fasilitas (FR-16: index, create, store, show, edit, update, destroy)
    Route::resource('facilities', FacilityController::class);

    // 3. Kelola Pengguna & Petugas (FR-13, FR-14, FR-15)
    Route::resource('users', UserController::class);

});