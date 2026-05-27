@extends('layouts.main')

@section('title', 'Edit Paket')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-700">Form Edit Paket</h3>
        </div>

        <form action="{{ route('paket.update', $paket->id) }}" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')

            @if(auth()->user()->role === 'admin')
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Outlet</label>
                <select name="outlet_id" class="w-full border border-slate-200 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all @error('outlet_id') border-red-300 bg-red-50 @enderror" required>
                    @foreach($outlets as $outlet)
                        <option value="{{ $outlet->id }}" {{ old('outlet_id', $paket->outlet_id) == $outlet->id ? 'selected' : '' }}>
                            {{ $outlet->name }}
                        </option>
                    @endforeach
                </select>
                @error('outlet_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            @else
                <input type="hidden" name="outlet_id" value="{{ $paket->outlet_id }}">
            @endif

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Jenis Paket</label>
                <select name="jenis" class="w-full border border-slate-200 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all @error('jenis') border-red-300 bg-red-50 @enderror" required>
                    <option value="kiloan" {{ old('jenis', $paket->jenis) == 'kiloan' ? 'selected' : '' }}>Kiloan</option>
                    <option value="selimut" {{ old('jenis', $paket->jenis) == 'selimut' ? 'selected' : '' }}>Selimut</option>
                    <option value="bed_cover" {{ old('jenis', $paket->jenis) == 'bed_cover' ? 'selected' : '' }}>Bed Cover</option>
                    <option value="kaos" {{ old('jenis', $paket->jenis) == 'kaos' ? 'selected' : '' }}>Kaos</option>
                    <option value="lainnya" {{ old('jenis', $paket->jenis) == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
                @error('jenis')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Paket</label>
                <input type="text" name="nama_paket" value="{{ old('nama_paket', $paket->nama_paket) }}" 
                    class="w-full border border-slate-200 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all @error('nama_paket') border-red-300 bg-red-50 @enderror" required>
                @error('nama_paket')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Harga (Rp)</label>
                <input type="number" name="harga" value="{{ old('harga', $paket->harga) }}" 
                    class="w-full border border-slate-200 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all @error('harga') border-red-300 bg-red-50 @enderror" min="0" required>
                @error('harga')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <a href="{{ route('paket.index') }}" class="px-6 py-2 border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 transition-all">Batal</a>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 font-semibold shadow-sm transition-all">Update Paket</button>
            </div>
        </form>
    </div>
</div>
@endsection