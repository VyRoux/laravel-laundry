@extends('layouts.main')

@section('title', 'Transaksi Baru')

@section('content')
<div class="bg-white rounded-lg shadow p-6 max-w-3xl" x-data="{
    items: [{ paket_id: '', qty: 1, keterangan: '' }],
    addRow() {
        this.items.push({ paket_id: '', qty: 1, keterangan: '' });
    },
    removeRow(index) {
        if (this.items.length > 1) {
            this.items.splice(index, 1);
        }
    }
}">
    <form action="{{ route('transaksi.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Outlet</label>
                <select name="outlet_id" class="w-full border rounded py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                    @foreach ($outlets as $outlet)
                        <option value="{{ $outlet->id }}" {{ auth()->user()->outlet_id == $outlet->id ? 'selected' : '' }}>
                            {{ $outlet->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Member</label>
                <select name="member_id" class="w-full border rounded py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                    <option value="" disabled selected>-- Pilih Member --</option>
                    @foreach ($members as $member)
                        <option value="{{ $member->id }}">{{ $member->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal</label>
                <input type="datetime-local" name="tgl" class="w-full border rounded py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ now()->format('Y-m-d\TH:i') }}" required>
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Batas Waktu</label>
                <input type="datetime-local" name="batas_waktu" class="w-full border rounded py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500" value="{{ now()->addDays(2)->format('Y-m-d\TH:i') }}" required>
            </div>
        </div>

        <div class="mb-6">
            <div class="flex justify-between items-center mb-3">
                <label class="text-gray-700 text-sm font-bold">Item Paket</label>
                <button type="button" @click="addRow()" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold">
                    + Tambah Item
                </button>
            </div>

            <div class="space-y-3">
                <template x-for="(item, index) in items" :key="index">
                    <div class="flex gap-3 items-start">
                        <select :name="'items[' + index + '][paket_id]'" class="flex-1 border rounded py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                            <option value="" disabled selected>-- Pilih Paket --</option>
                            @foreach ($pakets as $paket)
                                <option value="{{ $paket->id }}">{{ $paket->nama_paket }} - Rp {{ number_format($paket->harga, 0, ',', '.') }} ({{ $paket->jenis }})</option>
                            @endforeach
                        </select>

                        <input type="number" :name="'items[' + index + '][qty]'" step="0.1" min="0" placeholder="Qty" class="w-24 border rounded py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" required>

                        <input type="text" :name="'items[' + index + '][keterangan]'" placeholder="Keterangan" class="flex-1 border rounded py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">

                        <button type="button" @click="removeRow(index)" class="text-red-500 hover:text-red-700 font-bold px-2 py-2 text-sm" x-show="items.length > 1">
                            ✕
                        </button>
                    </div>
                </template>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Biaya Tambahan</label>
                <input type="number" name="biaya_tambahan" class="w-full border rounded py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="0" min="0">
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Diskon (%)</label>
                <input type="number" name="diskon" class="w-full border rounded py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="0" min="0" max="100">
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Pajak</label>
                <input type="number" name="pajak" class="w-full border rounded py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="0" min="0">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                <select name="status" class="w-full border rounded py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                    <option value="baru" selected>Baru</option>
                    <option value="proses">Proses</option>
                    <option value="selesai">Selesai</option>
                    <option value="diambil">Diambil</option>
                </select>
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Pembayaran</label>
                <select name="dibayar" class="w-full border rounded py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                    <option value="belum_dibayar" selected>Belum Dibayar</option>
                    <option value="dibayar">Dibayar</option>
                </select>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-indigo-600 text-white font-bold py-2 px-4 rounded hover:bg-indigo-700">
                Simpan Transaksi
            </button>
            <a href="{{ route('transaksi.index') }}" class="text-gray-600 hover:underline">Batal</a>
        </div>
    </form>
</div>
@endsection
