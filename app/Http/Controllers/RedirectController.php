<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectController extends Controller
{
    public function handle()
    {
        $user = Auth::user();

        // Jika user adalah admin atau superadmin, arahkan ke manajemen pengajuan
        if ($user->hasRole(['admin', 'superadmin', 'Pengajuan'])) {
            return redirect()->route('admin.pengajuan.index');
        }

                // Jika user adalah polda, arahkan ke halaman pengajuan polda
        if ($user->hasRole('polda')) {
            return redirect()->route('polda.pengajuan.index');
        }

        // Jika user adalah samsat, arahkan ke halaman pengajuan samsat
        if ($user->hasRole('samsat')) {
            return redirect()->route('samsat.pengajuan.index');
        }

        // Jika user adalah bapenda, arahkan ke halaman pengajuan bapenda
        if ($user->hasRole('bapenda')) {
            return redirect()->route('bapenda.pengajuan.index');
        }

        // Jika user adalah jasa raharja, arahkan ke halaman pengajuan jr
        if ($user->hasRole('jr')) {
            return redirect()->route('jr.pengajuan.index');
        }

        // Jika user adalah penulis, arahkan ke form buat pengajuan
        if ($user->hasRole('penulis')) {
            return redirect()->route('pengajuan.create');
        }

        // Fallback jika user tidak punya role di atas (misal: ke dashboard umum)
        return redirect('/dashboard');
    }
}