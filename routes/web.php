<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FacilityController; 
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\Officer\FacilityController as OfficerFacilityController;
use App\Http\Controllers\Officer\ReportController as OfficerReportController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\FacilityController as AdminFacilityController; 

Route::get('/', function () {
    return redirect()->route('reports.index');
});

// Modul Pelaporan Kerusakan Fasilitas (Aktor: Pengguna)
Route::prefix('reports')->name('reports.')->group(function () {
    // FR-07: Lihat status & riwayat laporan kerusakan
    Route::get('/', [ReportController::class, 'index'])->name('index');

    // FR-06: Laporkan kerusakan fasilitas
    Route::get('/create', [ReportController::class, 'create'])->name('create');
    Route::post('/', [ReportController::class, 'store'])->name('store');
});

// Modul Petugas: Antrean Laporan & Status Fasilitas (Aktor: Petugas)
Route::prefix('officer')->name('officer.')->group(function () {
    // FR-11: Antrean & Ubah status laporan kerusakan
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [OfficerReportController::class, 'index'])->name('index');
        Route::patch('/{report}/status', [OfficerReportController::class, 'update'])->name('update');
    });

    // FR-12: Tandai status fasilitas (dalam perbaikan / aktif)
    Route::prefix('facilities')->name('facilities.')->group(function () {
        Route::get('/', [OfficerFacilityController::class, 'index'])->name('index');
        Route::patch('/{facility}/status', [OfficerFacilityController::class, 'updateStatus'])->name('update-status');
    });
});
Route::view('/', 'landing')->name('home');
Route::view('/fasilitas', 'facilities')->name('facilities.index');

Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
});

Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
});

Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');

Route::get('/admin/facilities', [AdminFacilityController::class, 'index'])->name('admin.facilities.index');

Route::get('/admin/facilities/create', [AdminFacilityController::class, 'create'])->name('admin.facilities.create');
Route::post('/admin/facilities', [AdminFacilityController::class, 'store'])->name('admin.facilities.store');

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
