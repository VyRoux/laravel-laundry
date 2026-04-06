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
                    <input type="text" name="name" value="{{ $user->name }}" class="w-full border border-slate-200 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Username</label>
                    <input type="text" name="username" value="{{ $user->username }}" class="w-full border border-slate-200 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all" required>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Password</label>
                <input type="password" name="password" class="w-full border border-slate-200 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all" placeholder="Kosongkan jika tidak ingin ganti password">
                <p class="text-xs text-slate-400 mt-1 italic">*Minimal 6 karakter jika ingin diubah</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Outlet</label>
                    <select name="outlet_id" class="w-full border border-slate-200 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                        @foreach($outlets as $o)
                            <option value="{{ $o->id }}" {{ $user->outlet_id == $o->id ? 'selected' : '' }}>{{ $o->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Role</label>
                    <select name="role" class="w-full border border-slate-200 rounded-xl px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all">
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="kasir" {{ $user->role == 'kasir' ? 'selected' : '' }}>Kasir</option>
                        <option value="owner" {{ $user->role == 'owner' ? 'selected' : '' }}>Owner</option>
                    </select>
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