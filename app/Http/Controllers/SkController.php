<?php

namespace App\Http\Controllers;
use App\Models\Pengajuan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SkController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengajuan::where('user_id', Auth::id())
            ->with('kendaraans:id,pengajuan_id,status')
            ->withCount('kendaraans')
            ->latest();

        // Filter: search by nomor_pengajuan
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where('nomor_pengajuan', 'like', "%{$q}%");
        }

        // Filter: by status (draft, selesai, ditolak)
        if ($request->filled('status')) {
            $status = $request->status;

            if (!in_array($status, ['draft', 'selesai', 'ditolak'])) {
                // ignore unknown statuses
            } else {
                switch ($status) {
                    case 'draft':
                        // no kendaraans
                        $query->whereDoesntHave('kendaraans');
                        break;


                    case 'selesai':
                        // all kendaraans selesai (and at least one exists)
                        $query->whereHas('kendaraans')
                            ->whereDoesntHave('kendaraans', function ($q) {
                                $q->where('status', '<>', 'selesai');
                            });
                        break;

                    case 'pengajuan':
                        // has kendaraans, none ditolak, none diproses, and not all selesai
                        $query->whereHas('kendaraans')
                            ->whereDoesntHave('kendaraans', function ($q) {
                                $q->where('status', 'ditolak');
                            })
                            ->whereDoesntHave('kendaraans', function ($q) {
                                $q->where('status', 'diproses');
                            })
                            // ensure at least one kendaraan is not 'selesai' (so not all finished)
                            ->whereHas('kendaraans', function ($q) {
                                $q->where('status', '<>', 'selesai');
                            });
                        break;
                }
            }
        }

        $perPage = (int) $request->input('per_page', 10);
        $pengajuans = $query->paginate($perPage)->appends($request->except('page'));

        return view('sk.index', compact('pengajuans'));
    }

    public function create(Request $request)
    {
        return view('sk.create');
    }
}
