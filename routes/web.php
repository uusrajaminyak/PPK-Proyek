<?php

use App\Http\Controllers\Officer\FacilityController as OfficerFacilityController;
use App\Http\Controllers\Officer\ReportController as OfficerReportController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

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
