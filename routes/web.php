<?php

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

// Modul Antrean & Penanganan Kerusakan Fasilitas (Aktor: Petugas)
Route::prefix('officer')->name('officer.')->group(function () {
    Route::prefix('reports')->name('reports.')->group(function () {
        // FR-11: Antrean & Ubah status laporan kerusakan
        Route::get('/', [OfficerReportController::class, 'index'])->name('index');
        Route::patch('/{report}/status', [OfficerReportController::class, 'update'])->name('update');
    });
});
