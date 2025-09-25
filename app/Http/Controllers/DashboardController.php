<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik Terkini (Hari ini)
        $statsTerkini = Pengajuan::query()
            ->select('status', DB::raw('count(*) as total'))
            ->whereDate('created_at', Carbon::today())
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        // Statistik Bulan Ini
        $statsBulanIni = Pengajuan::query()
            ->select('status', DB::raw('count(*) as total'))
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        // Statistik Tahun Ini
        $statsTahunIni = Pengajuan::query()
            ->select('status', DB::raw('count(*) as total'))
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        return view('dashboard', compact('statsTerkini', 'statsBulanIni', 'statsTahunIni'));
    }
}