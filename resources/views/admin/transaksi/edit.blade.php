@extends('layouts.main')

@section('title', 'Edit Transaksi - ' . $transaksi->kode_invoice)

@section('content')
<div class="bg-white rounded-lg shadow p-6 max-w-lg">
    <div class="mb-6">
        <h3 class="text-lg font-bold text-slate-700">Edit Status Transaksi</h3>
        <p class="text-sm text-slate-500">Invoice: {{ $transaksi->kode_invoice }}</p>
        <p class="text-sm text-slate-500">Member: {{ $transaksi->member->name }}</p>
    </div>

    <form action="{{ route('transaksi.update', $transaksi->id) }}" method="POST">
        @csrf @method('PUT')

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Status Kerja</label>
            <select name="status" class="w-full border rounded py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                <option value="baru" {{ $transaksi->status == 'baru' ? 'selected' : '' }}>Baru</option>
                <option value="proses" {{ $transaksi->status == 'proses' ? 'selected' : '' }}>Proses</option>
                <option value="selesai" {{ $transaksi->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="diambil" {{ $transaksi->status == 'diambil' ? 'selected' : '' }}>Diambil</option>
            </select>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Status Pembayaran</label>
            <select name="dibayar" class="w-full border rounded py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                <option value="belum_dibayar" {{ $transaksi->dibayar == 'belum_dibayar' ? 'selected' : '' }}>Belum Dibayar</option>
                <option value="dibayar" {{ $transaksi->dibayar == 'dibayar' ? 'selected' : '' }}>Dibayar</option>
            </select>
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-indigo-600 text-white font-bold py-2 px-4 rounded hover:bg-indigo-700">
                Update Status
            </button>
            <a href="{{ route('transaksi.show', $transaksi->id) }}" class="text-gray-600 hover:underline">Batal</a>
        </div>
    </form>
</div>
@endsection
