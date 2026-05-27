@extends('layouts.main')

@section('title', 'Ringkasan Data')

@section('content')
    {{-- Card Selamat Datang (Muncul untuk semua role) --}}
    <div class="bg-indigo-600 rounded-2xl p-6 mb-6 text-white shadow-lg shadow-indigo-100">
        <h2 class="text-2xl font-bold">Halo, {{ auth()->user()->name }}!</h2>
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
                <h3 class="text-3xl font-bold text-slate-800">{{ $count_transaksi }}</h3>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm border-l-4 border-l-blue-500">
                <p class="text-slate-500 text-sm">Pelanggan Terdaftar</p>
                <h3 class="text-3xl font-bold text-slate-800">{{ $count_member }}</h3>
            </div>
        </div>

    @elseif(auth()->user()->role == 'owner')
        {{-- DASHBOARD OWNER: Fokus ke laporan keuangan --}}
        
        {{-- Card Ringkasan --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm border-l-4 border-l-emerald-500">
                <p class="text-slate-500 text-sm">Total Pendapatan Bersih</p>
                <h3 class="text-2xl font-bold text-slate-800">Rp {{ number_format($total_pendapatan_bersih, 0, ',', '.') }}</h3>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm border-l-4 border-l-blue-500">
                <p class="text-slate-500 text-sm">Transaksi Selesai & Diambil</p>
                <h3 class="text-3xl font-bold text-slate-800">{{ $transaksi_selesai }}</h3>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm border-l-4 border-l-amber-500">
                <p class="text-slate-500 text-sm">Belum Dibayar</p>
                <h3 class="text-3xl font-bold text-slate-800">{{ $transaksi_belum_bayar }}</h3>
            </div>
        </div>

        {{-- Grafik Pendapatan Bulanan --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm mb-6">
            <h4 class="font-bold text-slate-700 mb-4">Pendapatan 6 Bulan Terakhir</h4>
            <div class="space-y-4">
                @forelse($pendapatan_bulanan as $data)
                    @php
                        $net = $data->net;
                        $max_net = $pendapatan_bulanan->max(fn($d) => $d->net);
                        $width = $max_net > 0 ? ($net / $max_net) * 100 : 0;
                    @endphp
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-semibold text-slate-600">{{ $data->nama_bulan }}</span>
                            <span class="font-bold text-slate-800">Rp {{ number_format($net, 0, ',', '.') }} <span class="text-xs text-slate-400 font-normal">({{ $data->jumlah_transaksi }} transaksi)</span></span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-4 overflow-hidden">
                            <div class="h-4 bg-gradient-to-r from-indigo-500 to-blue-500 rounded-full transition-all" style="width: {{ $width }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="py-10 text-center text-slate-400 italic">
                        Belum ada data transaksi yang sudah selesai dan dibayar.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Transaksi Per Status --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <h4 class="font-bold text-slate-700 mb-4">Transaksi Berdasarkan Status</h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @php
                    $status_colors = [
                        'baru' => 'bg-slate-500',
                        'proses' => 'bg-blue-500',
                        'selesai' => 'bg-emerald-500',
                        'diambil' => 'bg-indigo-500',
                    ];
                @endphp
                @foreach($transaksi_per_status as $item)
                    <div class="text-center p-4 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="inline-block w-3 h-3 rounded-full {{ $status_colors[$item->status] ?? 'bg-slate-400' }} mb-2"></div>
                        <p class="text-xs text-slate-500 uppercase font-semibold capitalize">{{ $item->status }}</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $item->jumlah }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Ringkasan Per Outlet --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm mt-6">
            <h4 class="font-bold text-slate-700 mb-4">Ringkasan Per Outlet</h4>
            @if($outlet_summary->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($outlet_summary as $o)
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                    <p class="font-semibold text-slate-700">{{ $o->outlet }}</p>
                    <div class="mt-2 space-y-1 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Transaksi</span>
                            <span class="font-semibold">{{ $o->jumlah_transaksi }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Total Bersih</span>
                            <span class="font-semibold text-indigo-600">Rp {{ number_format($o->total_bersih, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="py-6 text-center text-slate-400 italic">Belum ada data transaksi.</div>
            @endif
        </div>

        {{-- Transaksi Terbaru --}}
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm mt-6">
            <div class="flex justify-between items-center mb-4">
                <h4 class="font-bold text-slate-700">Transaksi Terbaru</h4>
                <a href="{{ route('report.index') }}" class="text-xs font-semibold text-indigo-600 hover:underline">Lihat Laporan →</a>
            </div>
            @if($recent_transaksi->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="text-slate-500 text-xs uppercase font-semibold">
                        <tr>
                            <th class="pb-2 pr-4">Invoice</th>
                            <th class="pb-2 pr-4">Member</th>
                            <th class="pb-2 pr-4">Outlet</th>
                            <th class="pb-2 pr-4">Tanggal</th>
                            <th class="pb-2 pr-4">Status</th>
                            <th class="pb-2 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @foreach($recent_transaksi as $t)
                        <tr class="hover:bg-slate-50 transition-all">
                            <td class="py-3 pr-4 font-mono text-xs">
                                <a href="{{ route('transaksi.show', $t->id) }}" class="text-indigo-600 hover:underline">{{ $t->kode_invoice }}</a>
                            </td>
                            <td class="py-3 pr-4">{{ $t->member->name ?? '-' }}</td>
                            <td class="py-3 pr-4">{{ $t->outlet->name ?? '-' }}</td>
                            <td class="py-3 pr-4">{{ date('d/m/Y', strtotime($t->tgl)) }}</td>
                            <td class="py-3 pr-4">
                                <span class="px-2 py-0.5 rounded-lg text-xs font-semibold
                                    @if($t->status == 'baru') bg-blue-100 text-blue-700
                                    @elseif($t->status == 'proses') bg-yellow-100 text-yellow-700
                                    @elseif($t->status == 'selesai') bg-green-100 text-green-700
                                    @else bg-slate-100 text-slate-700 @endif">
                                    {{ ucfirst($t->status) }}
                                </span>
                            </td>
                            <td class="py-3 text-right font-semibold">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="py-6 text-center text-slate-400 italic">Belum ada transaksi.</div>
            @endif
        </div>
    @endif
@endsection