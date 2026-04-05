<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
    
});

// Login
Route::get('login', [LoginController::class, 'index'])->name('login');
Route::post('login', [LoginController::class, 'authenticate']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');


Route::middleware(['auth'])->prefix('dashboard')->group(function() {

    // Bisa diakses Admin & Kasir (Member & Transaksi)
    Route::middleware(['role:admin,kasir'])->group(function () {
        Route::resource('member', MemberController::class);
        // Route::resource('transaction', TransactionController::class);
    });

    // Admin (Outlet & User)
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('outlet', OutletController::class);
        Route::resource('user', UserController::class);
    });

    // Owner (Laporan)
    Route::middleware(['role:owner'])->group(function () {
        // Route::get('report', [ReportController::class, 'index']);
    });
});


