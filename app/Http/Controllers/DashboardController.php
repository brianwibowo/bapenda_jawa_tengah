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
            
        // Statistik Kepemilikan (semua waktu)
        $statsKepemilikan = DB::table('kendaraans')
            ->join('pemiliks', 'kendaraans.pemilik_id', '=', 'pemiliks.id')
            ->select('pemiliks.kepemilikan', DB::raw('count(*) as total'))
            ->groupBy('pemiliks.kepemilikan')
            ->get()
            ->keyBy('kepemilikan');

        $statsWilayah = DB::table('cabangs')
            ->leftJoin('pengajuans', 'cabangs.id', '=', 'pengajuans.cabang_id')
            ->leftJoin('kendaraans', 'pengajuans.id', '=', 'kendaraans.pengajuan_id')
            ->select('cabangs.nama', DB::raw('count(kendaraans.id) as total_pengajuan'))
            ->groupBy('cabangs.id', 'cabangs.nama')
            ->orderByDesc('total_pengajuan')
            ->get();

        // Statistik pengajuan berdasarkan jenis kendaraan
        $statsJenisKendaraan = DB::table('kendaraans')
            ->select('jenis_kendaraan as nama', DB::raw('count(id) as total_pengajuan'))
            ->groupBy('jenis_kendaraan')
            ->orderByDesc('total_pengajuan')
            ->get();

        // Statistik kendaraan berdasarkan tahun pembuatan (untuk chart)
        $statsTahunPembuatan = DB::table('kendaraans')
            ->select('tahun_pembuatan', DB::raw('count(id) as total'))
            ->groupBy('tahun_pembuatan')
            ->orderBy('tahun_pembuatan')
            ->get();

        $chartYears = $statsTahunPembuatan->pluck('tahun_pembuatan')->toArray();
        $chartTotals = $statsTahunPembuatan->pluck('total')->toArray();

        return view('dashboard', compact(
            'statsTerkini',
            'statsBulanIni',
            'statsTahunIni',
            'statsKepemilikan',
            'statsWilayah'
            , 'statsJenisKendaraan'
            , 'chartYears', 'chartTotals'
        ));
    }
}