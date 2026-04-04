<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectController extends Controller
{
    public function handle()
    {
        $user = Auth::user();

        // 1. Level Superadmin / Master
        if ($user->can('manage_users')) {
            return redirect()->route('admin.pengajuan.index');
        }

        // 2. Level Verifikator Instansi & Admin
        if ($user->can('view_menu_manajemen_pengajuan')) {
            return redirect()->route('admin.pengajuan.index');
        }
        
        // 3. Level Wajib Pajak / Publik
        if ($user->can('view_menu_daftar_pengajuan') || $user->can('view_menu_buat_pengajuan')) {
            // Arahkan ke pangkalan data histori milik mereka
            return redirect()->route('pengajuan.index');
        }

        // Fallback jika aneh
        return redirect('/dashboard');
    }
}