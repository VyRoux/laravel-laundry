<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use Illuminate\Http\Request;

class OutletController extends Controller
{
    public function index()
    {
        $outlets = Outlet::whereNull('deleted_at')->get();
        return view('admin.outlet.index', compact('outlets'));
    }

    public function create()
    {
        return view('admin.outlet.create');
    }

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

    public function show(Outlet $outlet)
    {
        //
    }

    public function edit(Outlet $outlet)
    {
        return view('admin.outlet.edit', compact('outlet'));
    }

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

    public function destroy(Outlet $outlet)
    {
        if ($outlet->users()->count() > 0) {
            return back()->with('error', 'Outlet tidak bisa dihapus karena masih memiliki pegawai!');
        }

        if ($outlet->transaksis()->count() > 0) {
            return back()->with('error', 'Outlet tidak bisa dihapus karena masih memiliki transaksi!');
        }

        if ($outlet->pakets()->count() > 0) {
            return back()->with('error', 'Outlet tidak bisa dihapus karena masih memiliki paket!');
        }

        $outlet->delete();

        return redirect()->route('outlet.index')
            ->with('success', 'Outlet berhasil dipindahkan ke tempat sampah.');
    }

    public function trashed()
    {
        $outlets = Outlet::onlyTrashed()->get();
        return view('admin.outlet.trashed', compact('outlets'));
    }

    public function restore($id)
    {
        $outlet = Outlet::withTrashed()->findOrFail($id);
        $outlet->restore();

        return redirect()->route('outlet.trashed')
            ->with('success', 'Outlet berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        $outlet = Outlet::withTrashed()->findOrFail($id);
        $outlet->forceDelete();

        return redirect()->route('outlet.trashed')
            ->with('success', 'Outlet berhasil dihapus permanen.');
    }
}
