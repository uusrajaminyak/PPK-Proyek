<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FacilityController as AdminFacilityController;
use App\Http\Controllers\Admin\OfficerController as AdminOfficerController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\Officer\DashboardController as OfficerDashboardController;
use App\Http\Controllers\Officer\FacilityController as OfficerFacilityController;
use App\Http\Controllers\Officer\ReportController as OfficerReportController;
use App\Http\Controllers\Officer\ReservationController as OfficerReservationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public & Landing Routes
|--------------------------------------------------------------------------
*/
Route::view('/', 'landing')->name('home');
Route::get('/fasilitas', [FacilityController::class, 'index'])->name('facilities.index');

/*
|--------------------------------------------------------------------------
| Modul Pelaporan Kerusakan Fasilitas (Aktor: Pengguna) - FR-06, FR-07
|--------------------------------------------------------------------------
*/
Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('index');
    Route::get('/create', [ReportController::class, 'create'])->name('create');
    Route::post('/', [ReportController::class, 'store'])->name('store');
});

/*
|--------------------------------------------------------------------------
| Modul Petugas: Antrean Laporan & Status Fasilitas - FR-11, FR-12
|--------------------------------------------------------------------------
*/
Route::prefix('officer')->name('officer.')->group(function () {
    Route::get('/dashboard', [OfficerDashboardController::class, 'index'])->middleware('auth')->name('dashboard');
    Route::get('/reservations', [OfficerReservationController::class, 'index'])->middleware('auth')->name('reservations.index');
    Route::get('/reservations/history', [OfficerReservationController::class, 'history'])->middleware('auth')->name('reservations.history');
    Route::get('/reservations/{reservation}', [OfficerReservationController::class, 'show'])->middleware('auth')->name('reservations.show');
    Route::patch('/reservations/{reservation}/status', [OfficerReservationController::class, 'updateStatus'])->middleware('auth')->name('reservations.update-status');

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [OfficerReportController::class, 'index'])->middleware('auth')->name('index');
        Route::get('/{report}', [OfficerReportController::class, 'show'])->middleware('auth')->name('show');
        Route::patch('/{report}/status', [OfficerReportController::class, 'update'])->middleware('auth')->name('update');
    });

    Route::prefix('facilities')->name('facilities.')->group(function () {
        Route::get('/', [OfficerFacilityController::class, 'index'])->middleware('auth')->name('index');
        Route::get('/{facility}', [OfficerFacilityController::class, 'show'])->middleware('auth')->name('show');
        Route::patch('/{facility}/status', [OfficerFacilityController::class, 'updateStatus'])->middleware('auth')->name('update-status');
    });
});

/*
|--------------------------------------------------------------------------
| Modul Admin: Dashboard, Facilities, Users, Officers, & Reservations - FR-13 s/d FR-17
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    // Dashboard & Rekap (FR-17)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Kelola Data Fasilitas (FR-16)
    Route::resource('facilities', AdminFacilityController::class);

    // Kelola Pengguna (FR-14, FR-15)
    Route::resource('users', UserController::class);

    // Kelola Petugas (FR-13)
    Route::get('/officers', [AdminOfficerController::class, 'index'])->name('officers.index');
    Route::get('/officers/create', [AdminOfficerController::class, 'create'])->name('officers.create');
    Route::post('/officers', [AdminOfficerController::class, 'store'])->name('officers.store');

    // Kelola & Rekap Reservasi
    Route::get('/reservations', [AdminReservationController::class, 'index'])->name('reservations.index');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes - FR-18, FR-19
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/login/staff', [AuthController::class, 'showStaffLogin'])->name('login.staff');
Route::post('/login/staff', [AuthController::class, 'loginStaff'])->name('login.staff.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Modul Reservasi Pengguna Terdaftar (Aktor: Pengguna) - FR-03, FR-04, FR-05
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/facilities/{facility}/reservation', [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/my-reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::post('/reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');
});
