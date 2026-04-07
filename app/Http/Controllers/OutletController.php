<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use Illuminate\Http\Request;

class OutletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua data outlet
        $outlets = Outlet::all();
        return view('admin.outlet.index', compact('outlets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.outlet.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'phone_number' => 'nullable',
        ]);

        Outlet::create($request->all());

        return redirect()->route('outlet.index')
            ->with('success', 'Outlet baru berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Outlet $outlet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Outlet $outlet)
    {
        return view('admin.outlet.edit', compact('outlet'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Outlet $outlet)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'phone_number' => 'required|numeric',
        ]);

        $data = $request->all();
        $outlet->update($data);
        return redirect()->route('outlet.index')
            ->with('success', 'Outlet berhasil diperbaharui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Outlet $outlet)
    {
        // Cek apakah ada user yang masih terhubung ke outlet ini
    if ($outlet->users()->count() > 0) {
        return back()->with('error', 'Outlet tidak bisa dihapus karena masih memiliki pegawai!');
    }

        $outlet->delete();
        return redirect()->route('outlet.index')
            ->with('success','Outlet berhasil dihapus.');
    }
}
