@extends('layouts.main')

@section('title', 'Ringkasan Data')

@section('content')
    {{-- Card Selamat Datang (Muncul untuk semua role) --}}
    <div class="bg-indigo-600 rounded-2xl p-6 mb-6 text-white shadow-lg shadow-indigo-100">
        <h2 class="text-2xl font-bold">Halo, {{ auth()->user()->name }}! 👋</h2>
        <p class="opacity-90">Selamat datang kembali di sistem Manajemen Laundry Ibu.</p>
    </div>

    {{-- Logika Berdasarkan Role --}}
    @if(auth()->user()->role == 'admin')
        {{-- DASHBOARD ADMIN: Fokus ke statistik sistem --}}
{{-- Bagian Admin --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <p class="text-slate-500 text-sm">Total Outlet</p>
        <h3 class="text-3xl font-bold text-slate-800">{{ $count_outlet }}</h3>
    </div>
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <p class="text-slate-500 text-sm">Total Pengguna</p>
        <h3 class="text-3xl font-bold text-slate-800">{{ $count_user }}</h3>
    </div>
    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <p class="text-slate-500 text-sm">Total Pelanggan</p>
        <h3 class="text-3xl font-bold text-slate-800">{{ $count_member }}</h3>
    </div>
</div>

    @elseif(auth()->user()->role == 'kasir')
        {{-- DASHBOARD KASIR: Fokus ke transaksi & pelanggan --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm border-l-4 border-l-emerald-500">
                <p class="text-slate-500 text-sm">Transaksi Hari Ini</p>
                {{-- Ganti 24 jadi variabel --}}
                <h3 class="text-3xl font-bold text-slate-800">{{ $count_transaksi }}</h3>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm border-l-4 border-l-blue-500">
                <p class="text-slate-500 text-sm">Pelanggan Terdaftar</p>
                {{-- Ganti 150 jadi variabel --}}
                <h3 class="text-3xl font-bold text-slate-800">{{ $count_member }}</h3>
            </div>
        </div>

    @elseif(auth()->user()->role == 'owner')
        {{-- DASHBOARD OWNER: Fokus ke laporan keuangan --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <h4 class="font-bold text-slate-700 mb-4">Grafik Pendapatan Bulanan</h4>
            <div class="h-48 bg-slate-50 rounded-xl flex items-center justify-center border-2 border-dashed border-slate-200">
                <p class="text-slate-400 text-sm text-center">Area Laporan Keuangan<br></p>
            </div>
        </div>
    @endif
@endsection