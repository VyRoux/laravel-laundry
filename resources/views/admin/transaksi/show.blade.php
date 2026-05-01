@extends('layouts.main')

@section('title', 'Detail Transaksi - ' . $transaksi->kode_invoice)

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden max-w-4xl">
    <div class="p-6 border-b border-slate-100 flex justify-between items-center">
        <div>
            <h3 class="text-lg font-bold text-slate-700">Invoice {{ $transaksi->kode_invoice }}</h3>
            <p class="text-sm text-slate-500">{{ \Carbon\Carbon::parse($transaksi->tgl)->format('d M Y, H:i') }}</p>
        </div>
        <a href="{{ route('transaksi.index') }}" class="text-gray-600 hover:text-gray-800 text-sm font-semibold">
            ← Kembali
        </a>
    </div>

    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h4 class="text-sm font-bold text-slate-500 uppercase tracking-wide mb-3">Informasi Pelanggan</h4>
                <div class="space-y-2">
                    <p class="text-slate-700"><span class="font-semibold">Nama:</span> {{ $transaksi->member->name }}</p>
                    <p class="text-slate-700"><span class="font-semibold">Alamat:</span> {{ $transaksi->member->address ?? '-' }}</p>
                    <p class="text-slate-700"><span class="font-semibold">Telepon:</span> {{ $transaksi->member->phone_number }}</p>
                </div>
            </div>

            <div>
                <h4 class="text-sm font-bold text-slate-500 uppercase tracking-wide mb-3">Informasi Transaksi</h4>
                <div class="space-y-2">
                    <p class="text-slate-700"><span class="font-semibold">Outlet:</span> {{ $transaksi->outlet->name }}</p>
                    <p class="text-slate-700"><span class="font-semibold">Batas Waktu:</span> {{ \Carbon\Carbon::parse($transaksi->batas_waktu)->format('d M Y, H:i') }}</p>
                    <p class="text-slate-700"><span class="font-semibold">Kasir:</span> {{ $transaksi->user->name }}</p>
                </div>
            </div>
        </div>

        <h4 class="text-sm font-bold text-slate-500 uppercase tracking-wide mb-3">Detail Item</h4>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-600 text-xs uppercase font-semibold">
                    <tr>
                        <th class="px-4 py-3">Paket</th>
                        <th class="px-4 py-3">Jenis</th>
                        <th class="px-4 py-3 text-right">Qty</th>
                        <th class="px-4 py-3 text-right">Harga</th>
                        <th class="px-4 py-3 text-right">Subtotal</th>
                        <th class="px-4 py-3">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($transaksi->details as $detail)
                    <tr class="hover:bg-slate-50">
                        <td class="px-4 py-3 font-medium text-slate-700">{{ $detail->paket->nama_paket }}</td>
                        <td class="px-4 py-3 text-sm text-slate-600 capitalize">{{ $detail->paket->jenis }}</td>
                        <td class="px-4 py-3 text-right font-mono">{{ $detail->qty }}</td>
                        <td class="px-4 py-3 text-right font-mono">Rp {{ number_format($detail->paket->harga, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-right font-mono font-bold">Rp {{ number_format($detail->qty * $detail->paket->harga, 0, ',', '.') }}</td>
                        <td class="px-4 py-3 text-sm text-slate-500">{{ $detail->keterangan ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex justify-end">
            <div class="w-72 space-y-2">
                @php
                    $subtotal = $transaksi->details->sum(function($d) { return $d->qty * $d->paket->harga; });
                    $diskonRupiah = $subtotal * ($transaksi->diskon / 100);
                    $total = $subtotal - $diskonRupiah + ($transaksi->biaya_tambahan ?? 0) + ($transaksi->pajak ?? 0);
                @endphp
                <div class="flex justify-between text-sm text-slate-600">
                    <span>Subtotal</span>
                    <span class="font-mono">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                @if($transaksi->diskon > 0)
                <div class="flex justify-between text-sm text-red-600">
                    <span>Diskon ({{ $transaksi->diskon }}%)</span>
                    <span class="font-mono">- Rp {{ number_format($diskonRupiah, 0, ',', '.') }}</span>
                </div>
                @endif
                @if($transaksi->biaya_tambahan > 0)
                <div class="flex justify-between text-sm text-slate-600">
                    <span>Biaya Tambahan</span>
                    <span class="font-mono">Rp {{ number_format($transaksi->biaya_tambahan, 0, ',', '.') }}</span>
                </div>
                @endif
                @if($transaksi->pajak > 0)
                <div class="flex justify-between text-sm text-slate-600">
                    <span>Pajak</span>
                    <span class="font-mono">Rp {{ number_format($transaksi->pajak, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="flex justify-between text-lg font-bold text-slate-800 border-t border-slate-200 pt-3">
                    <span>Total</span>
                    <span class="font-mono">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm pt-2">
                    <span class="font-semibold">Status:</span>
                    <span class="capitalize px-3 py-1 rounded-lg text-xs font-bold 
                        {{ $transaksi->status == 'baru' ? 'bg-blue-50 text-blue-600' : 
                            ($transaksi->status == 'proses' ? 'bg-amber-50 text-amber-600' : 
                            ($transaksi->status == 'selesai' ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-600')) }}">
                        {{ $transaksi->status }}
                    </span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="font-semibold">Pembayaran:</span>
                    <span class="capitalize px-3 py-1 rounded-lg text-xs font-bold 
                        {{ $transaksi->dibayar == 'dibayar' ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }}">
                        {{ str_replace('_', ' ', $transaksi->dibayar) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="mt-8 flex justify-between">
            <a href="{{ route('transaksi.edit', $transaksi->id) }}" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-all">
                Edit Status
            </a>
            <div class="space-x-3">
                <button onclick="window.print()" class="bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-all">
                    Cetak Invoice
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
