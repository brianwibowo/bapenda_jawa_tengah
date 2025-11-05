<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan; // <-- GANTI: Gunakan model Kendaraan
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // <-- TAMBAHKAN: Kita butuh DB facade
use Carbon\Carbon; // <-- TAMBAHKAN: Kita butuh Carbon untuk tanggal

class DashboardController extends Controller
{
    public function index()
    {
        // GANTI SEMUA QUERY UNTUK MENGAMBIL DARI 'kendaraans' BUKAN 'pengajuans'

        // Statistik Terkini (Hari ini)
        $statsTerkini = Kendaraan::query() // <-- PERBAIKAN DI SINI
            ->select('status', DB::raw('count(*) as total'))
            ->whereDate('created_at', Carbon::today())
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        // Statistik Bulan Ini
        $statsBulanIni = Kendaraan::query() // <-- PERBAIKAN DI SINI
            ->select('status', DB::raw('count(*) as total'))
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        // Statistik Tahun Ini
        $statsTahunIni = Kendaraan::query() // <-- PERBAIKAN DI SINI
            ->select('status', DB::raw('count(*) as total'))
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('status')
            ->get()
            ->keyBy('status');
            
        return view('dashboard', compact('statsTerkini', 'statsBulanIni', 'statsTahunIni'));
    }
}