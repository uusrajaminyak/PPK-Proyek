<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\FacilityController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
});

Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
});

Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');

Route::get('/facilities', [FacilityController::class, 'index'])->name('admin.facilities.index');

Route::get('/facilities/create', [FacilityController::class, 'create'])->name('admin.facilities.create');
Route::post('/facilities', [FacilityController::class, 'store'])->name('admin.facilities.store');

Route::get('/officers', [\App\Http\Controllers\Admin\OfficerController::class, 'index'])->name('admin.officers.index');

Route::get('/officers/create', [\App\Http\Controllers\Admin\OfficerController::class, 'create'])->name('admin.officers.create');
Route::post('/officers', [\App\Http\Controllers\Admin\OfficerController::class, 'store'])->name('admin.officers.store');
