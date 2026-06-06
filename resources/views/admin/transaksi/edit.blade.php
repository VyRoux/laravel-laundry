@extends('layouts.main')

@section('title', 'Edit Transaksi - ' . $transaksi->kode_invoice)

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-700">Edit Status Transaksi</h3>
            <p class="text-sm text-slate-500 mt-1">Invoice: {{ $transaksi->kode_invoice }} — Member: {{ $transaksi->member->name }} ({{ $transaksi->member->created_at->format('Ym') }}{{ sprintf('%03d', $transaksi->member->id) }})</p>
        </div>

        <form action="{{ route('transaksi.update', $transaksi->id) }}" method="POST" class="p-6 space-y-5">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Status Kerja</label>
                <select name="status" required
                    class="w-full border border-slate-200 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all @error('status') border-red-300 bg-red-50 @enderror">
                    <option value="baru" {{ old('status', $transaksi->status) == 'baru' ? 'selected' : '' }}>Baru</option>
                    <option value="proses" {{ old('status', $transaksi->status) == 'proses' ? 'selected' : '' }}>Proses</option>
                    <option value="selesai" {{ old('status', $transaksi->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="diambil" {{ old('status', $transaksi->status) == 'diambil' ? 'selected' : '' }}>Diambil</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Status Pembayaran</label>
                <select name="dibayar" required
                    class="w-full border border-slate-200 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all @error('dibayar') border-red-300 bg-red-50 @enderror">
                    <option value="belum_dibayar" {{ old('dibayar', $transaksi->dibayar) == 'belum_dibayar' ? 'selected' : '' }}>Belum Dibayar</option>
                    <option value="dibayar" {{ old('dibayar', $transaksi->dibayar) == 'dibayar' ? 'selected' : '' }}>Dibayar</option>
                </select>
                @error('dibayar')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between pt-4">
                <a href="{{ route('transaksi.show', $transaksi->id) }}" class="px-6 py-2 border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 transition-all">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 font-semibold shadow-sm transition-all">
                    Update Status
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
