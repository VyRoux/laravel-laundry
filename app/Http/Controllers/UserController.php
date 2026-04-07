<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Outlet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mengambil semua data user beserta relasinya
        $users = User::all();
        $outlets = Outlet::all();
        return view('admin.user.index', compact('users','outlets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $outlets = Outlet::all(); 
        return view('admin.user.create', compact('outlets'));
    }

    /**
     * Store a newly created resource in storage.
     */
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
            'password' => Hash::make($request->password), // Melakukan Hash password
            'outlet_id' => $request->outlet_id,
            'role' => $request->role,
        ]);

        return redirect()->route('user.index')
            ->with('success', 'User baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $outlets = Outlet::all();

        return view('admin.user.edit', compact('user','outlets'));
    }

    /**
     * Update the specified resource in storage.
     */
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if (auth()->id == $user->id) {
            return back() ->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }

        $user->delete();
        return redirect()->route('user.index')
            ->with('success','User berhasil dihapus');
        
    }
}
