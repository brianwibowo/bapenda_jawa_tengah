<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengajuan;
use Illuminate\Http\Request;

class PengajuanController extends Controller
{
    public function index()
    {
        $pengajuans = Pengajuan::with('user')->latest()->paginate(10);
        return view('admin.pengajuan.index', compact('pengajuans'));
    }

    public function show(Pengajuan $pengajuan)
    {
        // Ambil semua media dari koleksi 'dokumen_pengajuan'
        $dokumen = $pengajuan->getMedia('dokumen_pengajuan');
        return view('admin.pengajuan.show', compact('pengajuan', 'dokumen'));
    }

    public function updateStatus(Request $request, Pengajuan $pengajuan)
    {
        $request->validate([
            'status' => 'required|in:diproses,selesai',
            'catatan_admin' => 'nullable|string',
        ]);

        $pengajuan->update([
            'status' => $request->status,
            'catatan_admin' => $request->catatan_admin,
        ]);

        return redirect()->route('admin.pengajuan.show', $pengajuan)->with('success', 'Status pengajuan berhasil diperbarui.');
    }
}