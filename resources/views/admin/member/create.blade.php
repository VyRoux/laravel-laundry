@extends('layouts.main')

@section('title', 'Tambah Member Baru')

@section('content')
<div class="bg-white rounded-lg shadow p-6 max-w-lg">
    <form action="{{ route('member.store') }}" method="POST">
        @csrf 
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Pelanggan</label>
            <input type="text" name="name" class="w-full border rounded py-2 px-3 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Alamat</label>
            <textarea name="address" class="w-full border rounded py-2 px-3" required></textarea>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Nomor Telepon</label>
            <input type="text" name="phone_number" class="w-full border rounded py-2 px-3" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Jenis Kelamin</label>
            <select name="gender" class="w-full border rounded py-2 px-3">
                <option value="laki-laki">Laki-laki</option>
                <option value="perempuan">Perempuan</option>
            </select>
        </div>

        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">
                Simpan Member
            </button>
            <a href="{{ route('member.index') }}" class="text-gray-600 hover:underline">Batal</a>
        </div>
    </form>
</div>
@endsection