<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\ReservationController;
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

Route::get('/reservations', [\App\Http\Controllers\Admin\ReservationController::class, 'index'])->name('admin.reservations.index');
// Public: Halaman utama (daftar fasilitas)
Route::get('/', [FacilityController::class, 'index'])->name('home');

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Pengguna only: Reservasi
Route::middleware(['auth'])->group(function () {
    Route::get('/facilities/{facility}/reservation', [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/my-reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::post('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');
});
