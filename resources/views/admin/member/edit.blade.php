@extends('layouts.main')

@section('title', 'Edit Pelanggan')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-700">Form Edit Pelanggan</h3>
            <p class="text-xs text-slate-400 mt-1">ID: {{ $member->created_at->format('Ym') }}{{ sprintf('%03d', $member->id) }}</p>
        </div>

        <form action="{{ route('member.update', $member->id) }}" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Pelanggan</label>
                <input type="text" name="name" value="{{ old('name', $member->name) }}" 
                    class="w-full border border-slate-200 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all @error('name') border-red-300 bg-red-50 @enderror" required>
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Jenis Kelamin</label>
                <div class="flex space-x-6">
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="gender" value="laki-laki" {{ old('gender', $member->gender) == 'laki-laki' ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 focus:ring-indigo-500" required>
                        <span class="text-sm text-slate-700">Laki-laki</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="gender" value="perempuan" {{ old('gender', $member->gender) == 'perempuan' ? 'checked' : '' }} class="w-4 h-4 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-sm text-slate-700">Perempuan</span>
                    </label>
                </div>
                @error('gender')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Alamat</label>
                <textarea name="address" rows="3" 
                    class="w-full border border-slate-200 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all @error('address') border-red-300 bg-red-50 @enderror" required>{{ old('address', $member->address) }}</textarea>
                @error('address')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nomor Telepon</label>
                <input type="text" name="phone_number" value="{{ old('phone_number', $member->phone_number) }}" 
                    class="w-full border border-slate-200 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all @error('phone_number') border-red-300 bg-red-50 @enderror" required>
                @error('phone_number')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <a href="{{ route('member.index') }}" class="px-6 py-2 border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 transition-all">Batal</a>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 font-semibold shadow-sm transition-all">Update Pelanggan</button>
            </div>
        </form>
    </div>
</div>
@endsection