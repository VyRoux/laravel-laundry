@extends('layouts.main')

@section('title', 'Edit Member')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-700">Ubah Data Pelanggan</h3>
        </div>

        <form action="{{ route('member.update', $member->id) }}" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                <input type="text" name="name" value="{{ $member->name }}" class="w-full border border-slate-200 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all" required>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nomor Telepon</label>
                <input type="text" name="phone_number" value="{{ $member->phone_number }}" class="w-full border border-slate-200 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all" required>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Alamat</label>
                <textarea name="address" rows="3" class="w-full border border-slate-200 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">{{ $member->address }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Jenis Kelamin</label>
                <select name="gender" class="w-full border border-slate-200 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all bg-white">
                    <option value="laki-laki" {{ old('gender', $member->gender) == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="perempuan" {{ old('gender', $member->gender) == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <a href="{{ route('member.index') }}" class="px-6 py-2 border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 transition-all">Batal</a>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 font-semibold shadow-sm transition-all">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection