<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FacilityController as AdminFacilityController;
use App\Http\Controllers\Admin\OfficerController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\Officer\FacilityController as OfficerFacilityController;
use App\Http\Controllers\Officer\ReportController as OfficerReportController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ReportController;

use App\Http\Controllers\Officer\FacilityController as OfficerFacilityController;
use App\Http\Controllers\Officer\ReportController as OfficerReportController;

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\FacilityController as AdminFacilityController;
use App\Http\Controllers\Admin\OfficerController as AdminOfficerController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Admin\RecapController;

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
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [OfficerReportController::class, 'index'])->name('index');
        Route::patch('/{report}/status', [OfficerReportController::class, 'update'])->name('update');
    });

    Route::prefix('facilities')->name('facilities.')->group(function () {
        Route::get('/', [OfficerFacilityController::class, 'index'])->name('index');
        Route::patch('/{facility}/status', [OfficerFacilityController::class, 'updateStatus'])->name('update-status');
    });
});

/*
|--------------------------------------------------------------------------
| Modul Admin: Dashboard, Facilities, Users, Officers, Reservations, & Rekap - FR-13 s/d FR-17
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    // Dashboard (FR-17)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Kelola Data Fasilitas (FR-16)
    Route::resource('facilities', AdminFacilityController::class);

    // Kelola Pengguna (FR-14, FR-15)
    Route::resource('users', UserController::class);

    // Kelola Petugas (FR-13)
    Route::get('/officers', [AdminOfficerController::class, 'index'])->name('officers.index');
    Route::get('/officers/create', [AdminOfficerController::class, 'create'])->name('officers.create');
    Route::post('/officers', [AdminOfficerController::class, 'store'])->name('officers.store');

    // Kelola Reservasi
    Route::get('/reservations', [AdminReservationController::class, 'index'])->name('reservations.index');

Route::get('/admin/facilities/create', [AdminFacilityController::class, 'create'])->name('admin.facilities.create');
Route::post('/admin/facilities', [AdminFacilityController::class, 'store'])->name('admin.facilities.store');

Route::get('/officers', [OfficerController::class, 'index'])->name('admin.officers.index');

Route::get('/officers/create', [OfficerController::class, 'create'])->name('admin.officers.create');
Route::post('/officers', [OfficerController::class, 'store'])->name('admin.officers.store');

Route::get('/reservations', [App\Http\Controllers\Admin\ReservationController::class, 'index'])->name('admin.reservations.index');
    // Rekap & Ekspor Laporan (FR-17)
    Route::get('/recap', [RecapController::class, 'index'])->name('recap.index');
    Route::get('/recap/export', [RecapController::class, 'export'])->name('recap.export');
});

/*
| Authentication Routes - FR-18, FR-19
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
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
