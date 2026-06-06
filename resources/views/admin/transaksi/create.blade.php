@extends('layouts.main')

@section('title', 'Transaksi Baru')

@section('content')
@php
    $membersJson = $members->map(fn($m) => ['id' => $m->id, 'name' => $m->name, 'phone' => $m->phone_number, 'code' => $m->created_at->format('Ym') . sprintf('%03d', $m->id)]);
    $paketsJson = $pakets->map(fn($p) => ['id' => $p->id, 'nama' => $p->nama_paket, 'harga' => $p->harga, 'jenis' => $p->jenis]);

    $oldItems = old('items');
    if ($oldItems) {
        $initialItems = [];
        $initialPaketSearch = [];
        foreach ($oldItems as $item) {
            $paket = $pakets->firstWhere('id', $item['paket_id'] ?? null);
            $initialItems[] = [
                'paket_id' => $item['paket_id'] ?? '',
                'qty' => $item['qty'] ?? 1,
                'keterangan' => $item['keterangan'] ?? '',
            ];
            $initialPaketSearch[] = $paket->nama_paket ?? '';
        }
    } else {
        $initialItems = [['paket_id' => '', 'qty' => 1, 'keterangan' => '']];
        $initialPaketSearch = [''];
    }

    $oldMemberId = old('member_id');
    $oldMember = $oldMemberId ? $members->firstWhere('id', $oldMemberId) : null;
    $oldMemberJson = $oldMember ? ['id' => $oldMember->id, 'name' => $oldMember->name, 'phone' => $oldMember->phone_number, 'code' => $oldMember->created_at->format('Ym') . sprintf('%03d', $oldMember->id)] : null;
@endphp

<div class="max-w-4xl" x-data="{
    items: @js($initialItems),
    hargaMap: {{ $pakets->pluck('harga', 'id') }},
    diskon: {{ old('diskon', 0) }},
    pajak: {{ old('pajak', 0) }},
    biaya_tambahan: {{ old('biaya_tambahan', 0) }},
    memberSearch: '{{ $oldMember->name ?? '' }}',
    memberOpen: false,
    memberSelected: @js($oldMemberJson),
    members: {{ $membersJson }},
    pakets: {{ $paketsJson }},
    paketSearch: @js($initialPaketSearch),
    paketOpen: [],

    get filteredMembers() {
        if (!this.memberSearch) return this.members;
        const q = this.memberSearch.toLowerCase();
        return this.members.filter(m => m.name.toLowerCase().includes(q) || m.phone.includes(q) || m.code.includes(q));
    },

    selectMember(m) {
        this.memberSelected = m;
        this.memberSearch = m.name;
        this.memberOpen = false;
    },

    clearMember() {
        this.memberSelected = null;
        this.memberSearch = '';
    },

    filteredPakets(index) {
        const q = (this.paketSearch[index] || '').toLowerCase();
        if (!q) return this.pakets;
        return this.pakets.filter(p => p.nama.toLowerCase().includes(q) || p.jenis.includes(q));
    },

    selectPaket(index, p) {
        this.items[index].paket_id = p.id;
        this.paketSearch[index] = p.nama;
        this.paketOpen[index] = false;
    },

    addRow() {
        this.items.push({ paket_id: '', qty: 1, keterangan: '' });
    },

    removeRow(index) {
        if (this.items.length > 1) {
            this.items.splice(index, 1);
        }
    },

    get subtotal() {
        return this.items.reduce((sum, item) => {
            const harga = this.hargaMap[item.paket_id] || 0;
            return sum + (harga * item.qty);
        }, 0);
    },

    get diskonRupiah() {
        return this.subtotal * (this.diskon / 100);
    },

    get pajakRupiah() {
        return this.subtotal * (this.pajak / 100);
    },

    get grandTotal() {
        return this.subtotal - this.diskonRupiah + Number(this.biaya_tambahan) + this.pajakRupiah;
    },

    stepFor(item) {
        const p = this.pakets.find(p => p.id === item.paket_id);
        return p && p.jenis === 'kiloan' ? '0.1' : '1';
    },

    minFor(item) {
        const p = this.pakets.find(p => p.id === item.paket_id);
        return p && p.jenis === 'kiloan' ? '0' : '1';
    }
}">
    <form action="{{ route('transaksi.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Kolom Kiri: Form --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Data Transaksi --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h3 class="text-lg font-bold text-slate-700 mb-4">Data Transaksi</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Outlet</label>
                            @if(auth()->user()->role === 'admin')
                            <select name="outlet_id" class="w-full border rounded py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('outlet_id') border-red-300 bg-red-50 @enderror" required>
                                @foreach ($outlets as $outlet)
                                    <option value="{{ $outlet->id }}" {{ old('outlet_id', auth()->user()->outlet_id) == $outlet->id ? 'selected' : '' }}>
                                        {{ $outlet->name }}
                                    </option>
                                @endforeach
                            </select>
                            @else
                            <input type="hidden" name="outlet_id" value="{{ auth()->user()->outlet_id }}">
                            <div class="w-full border border-slate-200 rounded-xl px-4 py-2.5 bg-slate-50 text-slate-600 text-sm">
                                {{ auth()->user()->outlet->name ?? 'Outlet tidak ditemukan' }}
                            </div>
                            @endif
                            @error('outlet_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal</label>
                            <input type="datetime-local" name="tgl" value="{{ old('tgl', now()->format('Y-m-d\TH:i')) }}" 
                                class="w-full border rounded py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('tgl') border-red-300 bg-red-50 @enderror" required>
                            @error('tgl')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Batas Waktu</label>
                            <input type="datetime-local" name="batas_waktu" value="{{ old('batas_waktu', now()->addDays(2)->format('Y-m-d\TH:i')) }}" 
                                class="w-full border rounded py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('batas_waktu') border-red-300 bg-red-50 @enderror" required>
                            @error('batas_waktu')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- Member Searchable Select --}}
                    <div class="mb-4" x-data="{ open: false }">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Member</label>
                        <div class="relative" @click.outside="open = false; memberOpen = false">
                            <input type="text" x-model="memberSearch" @focus="memberOpen = true" @input="memberOpen = true"
                                class="w-full border rounded py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('member_id') border-red-300 bg-red-50 @enderror"
                                placeholder="Cari nama atau telepon member...">
                            <input type="hidden" name="member_id" x-model="memberSelected ? memberSelected.id : ''">
                            <template x-if="memberSelected">
                                <button type="button" @click="clearMember()" class="absolute right-2 top-2 text-slate-400 hover:text-red-500 text-sm">×</button>
                            </template>
                            <ul x-show="memberOpen && filteredMembers.length > 0" x-cloak
                                class="absolute z-20 w-full mt-1 bg-white border border-slate-200 rounded-xl shadow-lg max-h-48 overflow-y-auto">
                                <template x-for="m in filteredMembers" :key="m.id">
                                    <li @click="selectMember(m)" 
                                        class="px-4 py-2.5 hover:bg-indigo-50 cursor-pointer text-sm"
                                        :class="memberSelected && memberSelected.id === m.id ? 'bg-indigo-50 text-indigo-700' : 'text-slate-700'">
                                        <div class="flex items-center justify-between">
                                            <span x-text="m.name"></span>
                                            <span class="text-xs text-slate-400 font-mono ml-4" x-text="m.code"></span>
                                        </div>
                                        <div class="text-xs text-slate-400" x-text="'telp: ' + m.phone"></div>
                                    </li>
                                </template>
                            </ul>
                            <ul x-show="memberOpen && filteredMembers.length === 0 && memberSearch.length > 0" x-cloak
                                class="absolute z-20 w-full mt-1 bg-white border border-slate-200 rounded-xl shadow-lg">
                                <li class="px-4 py-3 text-sm text-slate-400 italic text-center">Member tidak ditemukan</li>
                            </ul>
                        </div>
                        @error('member_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Item Paket --}}
                    <div>
                        <div class="flex justify-between items-center mb-3">
                            <label class="text-gray-700 text-sm font-bold">Item Paket</label>
                            <button type="button" @click="addRow()" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold">+ Tambah Item</button>
                        </div>

                        <div class="space-y-3">
                            <template x-for="(item, index) in items" :key="index">
                                <div class="flex gap-2 items-start">
                                    <div class="flex-1 relative">
                                        <input type="text" x-model="paketSearch[index]" @focus="paketOpen[index] = true" @input="paketOpen[index] = true"
                                            class="w-full border rounded py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                            placeholder="Cari paket..." @click.outside="paketOpen[index] = false">
                                        <input type="hidden" :name="'items[' + index + '][paket_id]'" x-model="item.paket_id">
                                        <ul x-show="paketOpen[index] && filteredPakets(index).length > 0" x-cloak
                                            class="absolute z-20 w-full mt-1 bg-white border border-slate-200 rounded-xl shadow-lg max-h-40 overflow-y-auto">
                                            <template x-for="p in filteredPakets(index)" :key="p.id">
                                                <li @click="selectPaket(index, p)" 
                                                    class="px-3 py-2 hover:bg-indigo-50 cursor-pointer text-xs flex justify-between"
                                                    :class="item.paket_id === p.id ? 'bg-indigo-50 text-indigo-700' : 'text-slate-700'">
                                                    <span x-text="p.nama"></span>
                                                    <span class="text-slate-400">Rp <span x-text="p.harga.toLocaleString('id-ID')"></span></span>
                                                </li>
                                            </template>
                                        </ul>
                                        <ul x-show="paketOpen[index] && filteredPakets(index).length === 0 && (paketSearch[index] || '').length > 0" x-cloak
                                            class="absolute z-20 w-full mt-1 bg-white border border-slate-200 rounded-xl shadow-lg">
                                            <li class="px-3 py-2 text-xs text-slate-400 italic text-center">Paket tidak ditemukan</li>
                                        </ul>
                                    </div>

                                    <input type="number" :name="'items[' + index + '][qty]'" x-model="item.qty" :step="stepFor(item)" :min="minFor(item)" placeholder="Qty"
                                        class="w-20 border rounded py-2 px-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" required>

                                    <input type="text" :name="'items[' + index + '][keterangan]'" x-model="item.keterangan" placeholder="Ket"
                                        class="w-24 border rounded py-2 px-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">

                                    <button type="button" @click="removeRow(index)" class="text-red-500 hover:text-red-700 font-bold px-2 py-2 text-sm" x-show="items.length > 1">✕</button>
                                </div>
                            </template>
                        </div>
                        @error('items')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Biaya & Status --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <h3 class="text-lg font-bold text-slate-700 mb-4">Biaya & Status</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Biaya Tambahan</label>
                            <input type="number" name="biaya_tambahan" x-model="biaya_tambahan"
                                class="w-full border rounded py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('biaya_tambahan') border-red-300 bg-red-50 @enderror" placeholder="0" min="0">
                            @error('biaya_tambahan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Diskon (%)</label>
                            <input type="number" name="diskon" x-model="diskon"
                                class="w-full border rounded py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('diskon') border-red-300 bg-red-50 @enderror" placeholder="0" min="0" max="100">
                            @error('diskon')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Pajak (%)</label>
                            <input type="number" name="pajak" x-model="pajak"
                                class="w-full border rounded py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('pajak') border-red-300 bg-red-50 @enderror" placeholder="0" min="0" max="100">
                            @error('pajak')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                            <select name="status" class="w-full border rounded py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('status') border-red-300 bg-red-50 @enderror" required>
                                <option value="baru" {{ old('status', 'baru') == 'baru' ? 'selected' : '' }}>Baru</option>
                                <option value="proses" {{ old('status') == 'proses' ? 'selected' : '' }}>Proses</option>
                                <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="diambil" {{ old('status') == 'diambil' ? 'selected' : '' }}>Diambil</option>
                            </select>
                            @error('status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Pembayaran</label>
                            <select name="dibayar" class="w-full border rounded py-2 px-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('dibayar') border-red-300 bg-red-50 @enderror" required>
                                <option value="belum_dibayar" {{ old('dibayar', 'belum_dibayar') == 'belum_dibayar' ? 'selected' : '' }}>Belum Dibayar</option>
                                <option value="dibayar" {{ old('dibayar') == 'dibayar' ? 'selected' : '' }}>Dibayar</option>
                            </select>
                            @error('dibayar')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Ringkasan Total (Live) --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sticky top-6">
                    <h3 class="text-lg font-bold text-slate-700 mb-4">Ringkasan</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between text-slate-600">
                            <span>Subtotal</span>
                            <span class="font-mono font-semibold" x-text="'Rp ' + subtotal.toLocaleString('id-ID')">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-red-600" x-show="diskonRupiah > 0">
                            <span>Diskon (<span x-text="diskon"></span>%)</span>
                            <span class="font-mono" x-text="'- Rp ' + diskonRupiah.toLocaleString('id-ID')">- Rp 0</span>
                        </div>
                        <div class="flex justify-between text-slate-600" x-show="biaya_tambahan > 0">
                            <span>Biaya Tambahan</span>
                            <span class="font-mono" x-text="'Rp ' + Number(biaya_tambahan).toLocaleString('id-ID')">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-slate-600" x-show="pajakRupiah > 0">
                            <span>Pajak (<span x-text="pajak"></span>%)</span>
                            <span class="font-mono" x-text="'Rp ' + pajakRupiah.toLocaleString('id-ID')">Rp 0</span>
                        </div>
                        <div class="border-t-2 border-slate-800 pt-3 flex justify-between text-lg font-bold text-slate-900">
                            <span>GRAND TOTAL</span>
                            <span class="font-mono" x-text="'Rp ' + grandTotal.toLocaleString('id-ID')">Rp 0</span>
                        </div>
                    </div>

                    <hr class="my-6 border-slate-200">

                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl shadow-sm transition-all">
                        Simpan Transaksi
                    </button>
                    <a href="{{ route('transaksi.index') }}" class="block text-center mt-3 text-sm text-slate-500 hover:underline">Batal</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection