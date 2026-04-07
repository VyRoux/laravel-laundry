@extends('layouts.main')

@section('title', 'Tambah Member Baru')

@section('content')
<div class="max-w-2xl mx-auto">
    {{-- Card Wrapper --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-700">Pendaftaran Pelanggan Baru</h3>
            <p class="text-sm text-slate-500">Isi data pelanggan untuk mulai mencatat transaksi.</p>
        </div>

        {{-- Form --}}
        <form action="{{ route('member.store') }}" method="POST" class="p-6 space-y-4">
            @csrf 
            
            {{-- Nama --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" 
                       class="w-full border border-slate-200 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all @error('name') border-red-500 @enderror" 
                       placeholder="Nama sesuai KTP..." required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Telepon --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Nomor Telepon</label>
                    <input type="text" name="phone_number" value="{{ old('phone_number') }}" 
                           class="w-full border border-slate-200 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all @error('phone_number') border-red-500 @enderror" 
                           placeholder="08xxxx" required>
                </div>

                {{-- Jenis Kelamin --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Jenis Kelamin</label>
                    <select name="gender" class="w-full border border-slate-200 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all bg-white">
                        <option value="laki-laki" {{ old('gender') == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="perempuan" {{ old('gender') == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
            </div>

            {{-- Alamat --}}
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Alamat</label>
                <textarea name="address" rows="3" 
                          class="w-full border border-slate-200 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all @error('address') border-red-500 @enderror" 
                          placeholder="Alamat lengkap member...">{{ old('address') }}</textarea>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex justify-end space-x-3 pt-4 border-t border-slate-100">
                <a href="{{ route('member.index') }}" class="px-6 py-2 border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 transition-all">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 font-semibold shadow-md shadow-indigo-100 transition-all active:scale-95">
                    Simpan Member
                </button>
            </div>
        </form>
    </div>
</div>
@endsection