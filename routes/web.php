<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

// Halaman utama langsung ke login
Route::get('/', function () {
    return view('login');
});

// Auth / Login Routes
Route::get('login', [LoginController::class, 'index'])->name('login');
Route::post('login', [LoginController::class, 'authenticate']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Group Middleware Auth
Route::middleware(['auth'])->prefix('dashboard')->group(function() {

    // 1. Dashboard Utama
    Route::get('/', function () {
        // Data untuk Admin
        $count_outlet = \App\Models\Outlet::count();
        $count_user   = \App\Models\User::count();

        // Data untuk Kasir
        $count_member = \App\Models\Member::count();
        // $count_transaksi = \App\Models\Transaction::whereDate('tgl', date('Y-m-d'))->count(); 

        return view('dashboard.index', [
            'count_outlet' => $count_outlet,
            'count_user'   => $count_user,
            'count_member' => $count_member,
            'count_transaksi' => 0
        ]);
    })->name('dashboard');

    // Akses Admin & Kasir
    Route::middleware(['role:admin,kasir'])->group(function () {
        Route::resource('member', MemberController::class);
        // Route::resource('transaction', TransactionController::class);
    });

    // Akses Khusus Admin
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('outlet', OutletController::class);
        Route::resource('user', UserController::class);
    });

    // Akses Khusus Owner
    Route::middleware(['role:owner'])->group(function () {
        // Route::get('report', [ReportController::class, 'index']);
    });
});