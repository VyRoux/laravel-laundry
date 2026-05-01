<?php

use App\Http\Controllers\PaketController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

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
        $user = auth()->user();

        // Data untuk Admin
        $count_outlet = \App\Models\Outlet::count();
        $count_user   = \App\Models\User::count();

        // Data untuk Kasir
        $count_member = \App\Models\Member::count();
        $count_transaksi = \App\Models\Transaksi::whereNull('deleted_at')->whereDate('tgl', date('Y-m-d'))->count();

        // Data untuk Owner
        $transaksi_selesai = \App\Models\Transaksi::whereNull('deleted_at')->where('dibayar', 'dibayar')->count();

        $transaksi_belum_bayar = \App\Models\Transaksi::whereNull('deleted_at')->where('dibayar', 'belum_dibayar')->count();

        $total_pendapatan = \DB::table('tbl_transaksi')
            ->whereNull('tbl_transaksi.deleted_at')
            ->join('tbl_detail_transaksi', 'tbl_transaksi.id', '=', 'tbl_detail_transaksi.transaksi_id')
            ->whereNull('tbl_detail_transaksi.deleted_at')
            ->join('tbl_paket', 'tbl_detail_transaksi.paket_id', '=', 'tbl_paket.id')
            ->whereNull('tbl_paket.deleted_at')
            ->where('tbl_transaksi.dibayar', 'dibayar')
            ->select(
                \DB::raw('SUM(tbl_detail_transaksi.qty * tbl_paket.harga) as subtotal'),
                \DB::raw('COALESCE(SUM(tbl_transaksi.biaya_tambahan), 0) as biaya_tambahan'),
                \DB::raw('COALESCE(SUM(tbl_transaksi.diskon), 0) as diskon'),
                \DB::raw('COALESCE(SUM(tbl_transaksi.pajak), 0) as pajak')
            )
            ->first();

        $pendapatan_kotor = ($total_pendapatan->subtotal ?? 0) + ($total_pendapatan->biaya_tambahan ?? 0);
        $total_pendapatan_bersih = $pendapatan_kotor - ($total_pendapatan->diskon ?? 0) + ($total_pendapatan->pajak ?? 0);

        $pendapatan_bulanan = \DB::table('tbl_transaksi')
            ->whereNull('tbl_transaksi.deleted_at')
            ->join('tbl_detail_transaksi', 'tbl_transaksi.id', '=', 'tbl_detail_transaksi.transaksi_id')
            ->whereNull('tbl_detail_transaksi.deleted_at')
            ->join('tbl_paket', 'tbl_detail_transaksi.paket_id', '=', 'tbl_paket.id')
            ->whereNull('tbl_paket.deleted_at')
            ->where('tbl_transaksi.dibayar', 'dibayar')
            ->select(
                \DB::raw("DATE_FORMAT(tbl_transaksi.tgl, '%Y-%m') as bulan"),
                \DB::raw("DATE_FORMAT(tbl_transaksi.tgl, '%M %Y') as nama_bulan"),
                \DB::raw('COUNT(DISTINCT tbl_transaksi.id) as jumlah_transaksi'),
                \DB::raw('SUM(tbl_detail_transaksi.qty * tbl_paket.harga) as subtotal'),
                \DB::raw('COALESCE(SUM(tbl_transaksi.biaya_tambahan), 0) as biaya_tambahan'),
                \DB::raw('COALESCE(SUM(tbl_transaksi.diskon), 0) as diskon'),
                \DB::raw('COALESCE(SUM(tbl_transaksi.pajak), 0) as pajak')
            )
            ->groupBy('bulan', 'nama_bulan')
            ->orderBy('bulan', 'desc')
            ->limit(6)
            ->get()
            ->reverse();

        $transaksi_per_status = \DB::table('tbl_transaksi')
            ->whereNull('tbl_transaksi.deleted_at')
            ->select('status', \DB::raw('COUNT(*) as jumlah'))
            ->groupBy('status')
            ->get();

        return view('dashboard.index', [
            'count_outlet' => $count_outlet,
            'count_user'   => $count_user,
            'count_member' => $count_member,
            'count_transaksi' => $count_transaksi,
            'total_pendapatan_bersih' => $total_pendapatan_bersih,
            'transaksi_selesai' => $transaksi_selesai,
            'transaksi_belum_bayar' => $transaksi_belum_bayar,
            'pendapatan_bulanan' => $pendapatan_bulanan,
            'transaksi_per_status' => $transaksi_per_status,
        ]);
    })->name('dashboard');

    // Akses Admin & Kasir
    Route::middleware(['role:admin,kasir'])->group(function () {
        Route::get('member/trashed', [MemberController::class, 'trashed'])->name('member.trashed');
        Route::post('member/{id}/restore', [MemberController::class, 'restore'])->name('member.restore');
        Route::resource('member', MemberController::class);

        Route::get('paket/trashed', [PaketController::class, 'trashed'])->name('paket.trashed');
        Route::post('paket/{id}/restore', [PaketController::class, 'restore'])->name('paket.restore');
        Route::resource('paket', PaketController::class);

        Route::get('transaksi/trashed', [TransaksiController::class, 'trashed'])->name('transaksi.trashed');
        Route::post('transaksi/{id}/restore', [TransaksiController::class, 'restore'])->name('transaksi.restore');
        Route::resource('transaksi', TransaksiController::class);
    });

    // Akses Khusus Admin
    Route::middleware(['role:admin'])->group(function () {
        Route::delete('member/{id}/force', [MemberController::class, 'forceDelete'])->name('member.force');
        Route::delete('paket/{id}/force', [PaketController::class, 'forceDelete'])->name('paket.force');
        Route::delete('transaksi/{id}/force', [TransaksiController::class, 'forceDelete'])->name('transaksi.force');

        Route::get('outlet/trashed', [OutletController::class, 'trashed'])->name('outlet.trashed');
        Route::post('outlet/{id}/restore', [OutletController::class, 'restore'])->name('outlet.restore');
        Route::delete('outlet/{id}/force', [OutletController::class, 'forceDelete'])->name('outlet.force');
        Route::resource('outlet', OutletController::class);

        Route::get('user/trashed', [UserController::class, 'trashed'])->name('user.trashed');
        Route::post('user/{id}/restore', [UserController::class, 'restore'])->name('user.restore');
        Route::delete('user/{id}/force', [UserController::class, 'forceDelete'])->name('user.force');
        Route::resource('user', UserController::class);
    });

    // Akses Khusus Owner
    Route::middleware(['role:owner'])->group(function () {
        // Route::get('report', [ReportController::class, 'index']);
    });
});