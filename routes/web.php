<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('reports.create');
});

// FR-06: Pelaporan Kerusakan Fasilitas (Aktor: Pengguna)
Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('/create', [ReportController::class, 'create'])->name('create');
    Route::post('/', [ReportController::class, 'store'])->name('store');
});
