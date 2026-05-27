@extends('layouts.main')

@section('title', 'Invoice ' . $transaksi->kode_invoice)

<style>
@media print {
    body * { visibility: hidden; }
    .invoice-print, .invoice-print * { visibility: visible; }
    .invoice-print { position: absolute; left: 0; top: 0; width: 100%; }
    aside, header, nav { display: none !important; }
    .no-print { display: none !important; }
}
</style>

@section('content')
<div class="invoice-print max-w-4xl mx-auto bg-white border border-slate-200 shadow-lg">
    <!-- Kop Toko -->
    <div class="text-center border-b-2 border-slate-800 p-6">
        <h1 class="text-3xl font-bold text-slate-800">{{ $transaksi->outlet->name }}</h1>
        <p class="text-slate-600 text-sm mt-1">{{ $transaksi->outlet->address }}</p>
        <p class="text-slate-600 text-sm">Telp: {{ $transaksi->outlet->phone_number }}</p>
        <div class="mt-2 pt-2 border-t border-slate-300">
            <p class="text-xs text-slate-500">Laundry Ibu &copy; 2026</p>
        </div>
    </div>

    <!-- Header Invoice -->
    <div class="p-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h2 class="text-2xl font-bold text-indigo-700">INVOICE</h2>
                <p class="text-lg font-mono font-bold text-slate-800 mt-1">{{ $transaksi->kode_invoice }}</p>
            </div>
            <div class="text-right">
                <p class="text-sm text-slate-600">Tanggal: {{ \Carbon\Carbon::parse($transaksi->tgl)->format('d F Y, H:i') }}</p>
                <p class="text-sm text-slate-600">Batas Waktu: {{ \Carbon\Carbon::parse($transaksi->batas_waktu)->format('d F Y, H:i') }}</p>
                <span class="inline-block mt-1 capitalize px-3 py-1 rounded-lg text-xs font-bold 
                    {{ $transaksi->status == 'baru' ? 'bg-blue-50 text-blue-600' : 
                        ($transaksi->status == 'proses' ? 'bg-amber-50 text-amber-600' : 
                        ($transaksi->status == 'selesai' ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-600')) }}">
                    {{ $transaksi->status }}
                </span>
            </div>
        </div>

        <!-- Informasi Pelanggan & Transaksi -->
        <div class="grid grid-cols-2 gap-4 mb-6 border border-slate-200 rounded-lg p-4">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase mb-2 border-b border-slate-200 pb-1">Informasi Pelanggan</p>
                <p class="text-sm text-slate-700"><span class="font-semibold">Nama:</span> {{ $transaksi->member->name }}</p>
                <p class="text-sm text-slate-700"><span class="font-semibold">Alamat:</span> {{ $transaksi->member->address ?? '-' }}</p>
                <p class="text-sm text-slate-700"><span class="font-semibold">Telepon:</span> {{ $transaksi->member->phone_number }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase mb-2 border-b border-slate-200 pb-1">Informasi Transaksi</p>
                <p class="text-sm text-slate-700"><span class="font-semibold">Outlet:</span> {{ $transaksi->outlet->name }}</p>
                <p class="text-sm text-slate-700"><span class="font-semibold">Kasir:</span> {{ $transaksi->user->name }}</p>
                <p class="text-sm text-slate-700"><span class="font-semibold">Pembayaran:</span> {{ str_replace('_', ' ', $transaksi->dibayar) }}</p>
            </div>
        </div>

        <!-- Tabel Item -->
        <table class="w-full text-left border-collapse mb-6">
            <thead>
                <tr class="bg-slate-100">
                    <th class="border border-slate-300 px-3 py-2 text-xs font-bold text-slate-600 uppercase">No</th>
                    <th class="border border-slate-300 px-3 py-2 text-xs font-bold text-slate-600 uppercase">Paket</th>
                    <th class="border border-slate-300 px-3 py-2 text-xs font-bold text-slate-600 uppercase">Jenis</th>
                    <th class="border border-slate-300 px-3 py-2 text-xs font-bold text-slate-600 uppercase text-right">Qty</th>
                    <th class="border border-slate-300 px-3 py-2 text-xs font-bold text-slate-600 uppercase text-right">Harga Satuan</th>
                    <th class="border border-slate-300 px-3 py-2 text-xs font-bold text-slate-600 uppercase text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaksi->details as $index => $detail)
                <tr>
                    <td class="border border-slate-300 px-3 py-2 text-sm text-center">{{ $index + 1 }}</td>
                    <td class="border border-slate-300 px-3 py-2 text-sm font-medium text-slate-700">{{ $detail->paket->nama_paket }}</td>
                    <td class="border border-slate-300 px-3 py-2 text-sm text-slate-600 capitalize">{{ $detail->paket->jenis }}</td>
                    <td class="border border-slate-300 px-3 py-2 text-sm text-right font-mono">{{ $detail->qty }}</td>
                    <td class="border border-slate-300 px-3 py-2 text-sm text-right font-mono">Rp {{ number_format($detail->paket->harga, 0, ',', '.') }}</td>
                    <td class="border border-slate-300 px-3 py-2 text-sm text-right font-mono font-bold">Rp {{ number_format($detail->qty * $detail->paket->harga, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Ringkasan Total -->
        <div class="flex justify-end mb-6">
            <div class="w-72 border border-slate-300 rounded-lg p-4 space-y-2">
                @php
                    $subtotal = $transaksi->details->sum(function($d) { return $d->qty * $d->paket->harga; });
                    $diskonRupiah = $subtotal * ($transaksi->diskon / 100);
                    $pajakRupiah = $subtotal * ($transaksi->pajak / 100);
                    $total = $subtotal - $diskonRupiah + ($transaksi->biaya_tambahan ?? 0) + $pajakRupiah;
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
                    <span>Pajak ({{ $transaksi->pajak }}%)</span>
                    <span class="font-mono">Rp {{ number_format($pajakRupiah, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="flex justify-between text-xl font-bold text-slate-900 border-t-2 border-slate-800 pt-3">
                    <span>GRAND TOTAL</span>
                    <span class="font-mono">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Tanda Tangan -->
        <div class="grid grid-cols-2 gap-8 mb-6">
            <div class="text-center pt-16">
                <div class="border-b border-slate-400 w-48 mx-auto"></div>
                <p class="text-xs text-slate-500 mt-2">Tanda Tangan Pelanggan</p>
            </div>
            <div class="text-center pt-16">
                <div class="border-b border-slate-400 w-48 mx-auto"></div>
                <p class="text-xs text-slate-500 mt-2">Tanda Tangan Kasir</p>
            </div>
        </div>

        <!-- Tombol Aksi (hanya di layar, tidak dicetak) -->
        <div class="no-print mt-8 flex justify-between items-center">
            <a href="{{ route('transaksi.index') }}" class="bg-slate-500 hover:bg-slate-600 text-white px-6 py-2 rounded-xl text-sm font-semibold transition-all">
                ← Kembali
            </a>
            <div class="flex gap-3">
                <a href="{{ route('transaksi.edit', $transaksi->id) }}" class="bg-amber-500 hover:bg-amber-600 text-white px-6 py-2 rounded-xl text-sm font-semibold transition-all">
                    Edit Status
                </a>
                <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-xl text-sm font-semibold transition-all">
                    Cetak Invoice
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
