<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login'); 
});

Route::middleware('guest')->group(function(){
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.process');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware('level:admin')->group(function() {
    Route::resource('kategori', KategoriController::class)->except('show');
    Route::resource('produk', ProdukController::class)->except('show');
    Route::resource('user', UserController::class)->except('show');
});

Route::middleware('level:admin,kasir')->group(function() {
    Route::resource('pelanggan', PelangganController::class)->except('show');
    Route::resource('penjualan', PenjualanController::class)
        ->only(['index', 'create', 'store', 'show']);
     Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
});

});