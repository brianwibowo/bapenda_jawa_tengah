<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cabang;
use Illuminate\Http\Request;

class CabangController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $query = Cabang::query();

        if ($search) {
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('wilayah', 'like', "%{$search}%");
        }

        $cabangs = $query->orderBy('wilayah')->paginate(10)->withQueryString();

        return view('admin.cabangs.index', compact('cabangs', 'search'));
    }

    public function create()
    {
        return view('admin.cabangs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'wilayah' => 'required|string|max:255',
        ]);

        Cabang::create($request->only(['nama', 'wilayah']));

        return redirect()->route('admin.cabangs.index')->with('success', 'Cabang berhasil ditambahkan.');
    }

    public function edit(Cabang $cabang)
    {
        return view('admin.cabangs.edit', compact('cabang'));
    }

    public function update(Request $request, Cabang $cabang)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'wilayah' => 'required|string|max:255',
        ]);

        $cabang->update($request->only(['nama', 'wilayah']));

        return redirect()->route('admin.cabangs.index')->with('success', 'Cabang berhasil diperbarui.');
    }

    public function destroy(Cabang $cabang)
    {
        $cabang->delete();

        return redirect()->route('admin.cabangs.index')->with('success', 'Cabang berhasil dihapus.');
    }
}
