<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Outlet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::whereNull('deleted_at')->get();
        $outlets = Outlet::whereNull('deleted_at')->get();
        return view('admin.user.index', compact('users','outlets'));
    }

    public function create()
    {
        $outlets = Outlet::whereNull('deleted_at')->get();
        return view('admin.user.create', compact('outlets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:tbl_user',
            'password' => 'required|min:6',
            'outlet_id' => 'required',
            'role' => 'required|in:admin,kasir,owner',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'outlet_id' => $request->outlet_id,
            'role' => $request->role,
        ]);

        return redirect()->route('user.index')
            ->with('success', 'User baru berhasil ditambahkan.');
    }

    public function show(User $user)
    {
        //
    }

    public function edit(User $user)
    {
        $outlets = Outlet::whereNull('deleted_at')->get();
        return view('admin.user.edit', compact('user','outlets'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:tbl_user,username,'.$user->id,
            'outlet_id' => 'required',
            'role' => 'required|in:admin,kasir,owner',
        ]);

        $data = $request->only([
            'name',
            'username',
            'outlet_id',
            'role',
        ]);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        return redirect()->route('user.index')
            ->with('success', 'User berhasil diperbaharui.');
    }

    public function destroy(User $user)
    {
        if (auth()->id() == $user->id) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }

        $user->delete();

        return redirect()->route('user.index')
            ->with('success', 'User berhasil dipindahkan ke tempat sampah.');
    }

    public function trashed()
    {
        $users = User::onlyTrashed()->with('outlet')->get();
        return view('admin.user.trashed', compact('users'));
    }

    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('user.trashed')
            ->with('success', 'User berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->forceDelete();

        return redirect()->route('user.trashed')
            ->with('success', 'User berhasil dihapus permanen.');
    }
}
