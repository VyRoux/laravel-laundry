<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use App\Models\Outlet;
use Illuminate\Http\Request;

class PaketController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $paket = Paket::with('outlet')->whereNull('deleted_at')->get();
        } else {
            $paket = Paket::with('outlet')
                ->where('outlet_id', $user->outlet_id)
                ->whereNull('deleted_at')
                ->get();
        }

        return view('admin.paket.index', compact('paket'));
    }

    public function create()
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $outlets = Outlet::whereNull('deleted_at')->get();
        } else {
            $outlets = Outlet::where('id', $user->outlet_id)->get();
        }

        return view('admin.paket.create', compact('outlets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'outlet_id'    => 'required|exists:tbl_outlet,id',
            'jenis'        => 'required|in:kiloan,selimut,bed_cover,kaos,lainnya',
            'nama_paket'   => 'required|max:100',
            'harga'        => 'required|integer|min:0',
        ]);

        Paket::create($request->all());

        return redirect()->route('paket.index')
            ->with('success', 'Paket berhasil ditambahkan.');
    }

    public function show(Paket $paket)
    {
        //
    }

    public function edit(Paket $paket)
    {
        $user = auth()->user();

        if ($user->role === 'admin') {
            $outlets = Outlet::whereNull('deleted_at')->get();
        } else {
            $outlets = Outlet::where('id', $user->outlet_id)->get();
        }

        return view('admin.paket.edit', compact('paket', 'outlets'));
    }

    public function update(Request $request, Paket $paket)
    {
        $request->validate([
            'outlet_id'    => 'required|exists:tbl_outlet,id',
            'jenis'        => 'required|in:kiloan,selimut,bed_cover,kaos,lainnya',
            'nama_paket'   => 'required|max:100',
            'harga'        => 'required|integer|min:0',
        ]);

        $paket->update($request->all());

        return redirect()->route('paket.index')
            ->with('success', 'Paket berhasil diperbarui.');
    }

    public function destroy(Paket $paket)
    {
        if ($paket->details()->count() > 0) {
            return back()->with('error', 'Paket tidak bisa dihapus karena sudah digunakan dalam transaksi!');
        }

        $paket->delete();

        return redirect()->route('paket.index')
            ->with('success', 'Paket berhasil dipindahkan ke tempat sampah.');
    }

    public function trashed()
    {
        $paket = Paket::with('outlet')->onlyTrashed()->get();
        return view('admin.paket.trashed', compact('paket'));
    }

    public function restore($id)
    {
        $paket = Paket::withTrashed()->findOrFail($id);
        $paket->restore();

        return redirect()->route('paket.trashed')
            ->with('success', 'Paket berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        if (auth()->user()->role !== 'admin') {
            return back()->with('error', 'Hanya admin yang bisa menghapus permanen!');
        }

        $paket = Paket::withTrashed()->findOrFail($id);
        $paket->forceDelete();

        return redirect()->route('paket.trashed')
            ->with('success', 'Paket berhasil dihapus permanen.');
    }
}
