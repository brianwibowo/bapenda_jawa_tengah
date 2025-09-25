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
        if ($user->hasRole(['admin', 'superadmin'])) {
            return redirect()->route('admin.pengajuan.index');
        }

        // Jika user adalah penulis, arahkan ke form buat pengajuan
        if ($user->hasRole('penulis')) {
            return redirect()->route('pengajuan.create');
        }

        // Fallback jika user tidak punya role di atas (misal: ke dashboard umum)
        return redirect('/dashboard');
    }
}