@extends('layouts.main')

@section('title', 'Edit Pengguna')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-700">Edit Akun Staff</h3>
        </div>

        <form action="{{ route('user.update', $user->id) }}" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Nama</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                        class="w-full border border-slate-200 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all @error('name') border-red-300 bg-red-50 @enderror" required>
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Username</label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}" 
                        class="w-full border border-slate-200 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all @error('username') border-red-300 bg-red-50 @enderror" required>
                    @error('username')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Password</label>
                <input type="password" name="password" 
                    class="w-full border border-slate-200 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all @error('password') border-red-300 bg-red-50 @enderror" placeholder="Kosongkan jika tidak ingin ganti password">
                <p class="text-xs text-slate-400 mt-1 italic">*Minimal 6 karakter jika ingin diubah</p>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Outlet</label>
                    <select name="outlet_id" class="w-full border border-slate-200 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all @error('outlet_id') border-red-300 bg-red-50 @enderror">
                        @foreach($outlets as $o)
                            <option value="{{ $o->id }}" {{ old('outlet_id', $user->outlet_id) == $o->id ? 'selected' : '' }}>{{ $o->name }}</option>
                        @endforeach
                    </select>
                    @error('outlet_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Role</label>
                    <select name="role" class="w-full border border-slate-200 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all @error('role') border-red-300 bg-red-50 @enderror">
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="kasir" {{ old('role', $user->role) == 'kasir' ? 'selected' : '' }}>Kasir</option>
                        <option value="owner" {{ old('role', $user->role) == 'owner' ? 'selected' : '' }}>Owner</option>
                    </select>
                    @error('role')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <a href="{{ route('user.index') }}" class="px-6 py-2 border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 transition-all">Batal</a>
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 font-semibold shadow-sm transition-all">Update User</button>
            </div>
        </form>
    </div>
</div>
@endsection