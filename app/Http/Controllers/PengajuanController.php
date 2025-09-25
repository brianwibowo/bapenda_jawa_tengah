<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengajuanController extends Controller
{
    public function create()
    {
        return view('pengajuan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'surat_permohonan' => 'required|mimes:pdf,docx|max:2048',
            'surat_pernyataan' => 'required|mimes:pdf,docx|max:2048',
            'ktp' => 'required|mimes:pdf,docx,jpg,png|max:2048',
            'bpkb' => 'required|mimes:pdf,docx|max:2048',
            'tbpkp' => 'required|mimes:pdf,docx|max:2048',
            'cek_fisik' => 'required|mimes:pdf,docx|max:2048',
            'foto_ranmor' => 'required|image|max:2048',
            'stnk' => 'required|mimes:pdf,docx|max:2048',
        ]);

        // Buat pengajuan baru
        $pengajuan = Pengajuan::create([
            'user_id' => Auth::id(),
            'status' => 'pengajuan',
        ]);

        // Upload semua file
        if ($request->hasFile('surat_permohonan')) {
            $pengajuan->addMediaFromRequest('surat_permohonan')->toMediaCollection('dokumen_pengajuan');
        }
        if ($request->hasFile('surat_pernyataan')) {
            $pengajuan->addMediaFromRequest('surat_pernyataan')->toMediaCollection('dokumen_pengajuan');
        }
        if ($request->hasFile('ktp')) {
            $pengajuan->addMediaFromRequest('ktp')->toMediaCollection('dokumen_pengajuan');
        }
        if ($request->hasFile('bpkb')) {
            $pengajuan->addMediaFromRequest('bpkb')->toMediaCollection('dokumen_pengajuan');
        }
        if ($request->hasFile('tbpkp')) {
            $pengajuan->addMediaFromRequest('tbpkp')->toMediaCollection('dokumen_pengajuan');
        }
        if ($request->hasFile('cek_fisik')) {
            $pengajuan->addMediaFromRequest('cek_fisik')->toMediaCollection('dokumen_pengajuan');
        }
        if ($request->hasFile('foto_ranmor')) {
            $pengajuan->addMediaFromRequest('foto_ranmor')->toMediaCollection('dokumen_pengajuan');
        }
        if ($request->hasFile('stnk')) {
            $pengajuan->addMediaFromRequest('stnk')->toMediaCollection('dokumen_pengajuan');
        }

        return redirect()->route('dashboard')->with('success', 'Berkas berhasil diajukan!');
    }
}