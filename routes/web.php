<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\Admin\PengajuanController as AdminPengajuanController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\FrameController;
use App\Http\Controllers\SuratKeputusanController as SKController;
use App\Http\Controllers\SuratPengajuanController as SPController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Route untuk halaman utama (publik)
Route::get('/', function () {
    return view('welcome');
});

// Memuat route-route otentikasi (login, register, dll.)
require __DIR__ . '/auth.php';


// == GRUP UNTUK SEMUA ROUTE YANG MEMBUTUHKAN LOGIN ==
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/redirect', [RedirectController::class, 'handle'])->name('redirect.after.login');


    // ===========================================================
    // == ROUTE PENGAJUAN (WAJIB PAJAK / UMUM) ===
    // ===========================================================

    // Melihat daftar miliknya
    Route::get('/pengajuan-saya', [PengajuanController::class, 'index'])
        ->name('pengajuan.index')
        ->middleware('permission:view_menu_daftar_pengajuan');

    // Form Bikin Baru
    Route::get('/pengajuan/buat', [PengajuanController::class, 'create'])
        ->name('pengajuan.create')
        ->middleware('permission:view_menu_buat_pengajuan');

    // Proses Create
    Route::post('/pengajuan', [PengajuanController::class, 'store'])
        ->name('pengajuan.store')
        ->middleware('permission:create_pengajuan_baru');

    // Buka Detail / View (Harus punya izin view)
    Route::get('/pengajuan/{pengajuan}', [PengajuanController::class, 'show'])
        ->name('pengajuan.show')
        ->middleware('permission:view_menu_daftar_pengajuan');

    Route::delete('/pengajuan/{pengajuan}', [PengajuanController::class, 'destroy'])->name('pengajuan.destroy');
    Route::get('/pengajuan/{pengajuan}/log/{logId}', [PengajuanController::class, 'showLog'])->name('pengajuan.log.show');
    Route::post('/pengajuan/{pengajuan}/log', [PengajuanController::class, 'storeLog'])->name('pengajuan.log.store');
    Route::get('/pengajuan/{pengajuan}/pilih-sk', [PengajuanController::class, 'pilihSk'])->name('pengajuan.pilih_sk');

    Route::prefix('kendaraans')->group(function () {
        Route::post('/', [PengajuanController::class, 'storeKendaraan'])->name('kendaraan.store');
        Route::get('/{pengajuan}/tambah-kendaraan', [KendaraanController::class, 'create'])->name('kendaraan.create');
        Route::post('/{pengajuan}/simpan-kendaraan-lama', [KendaraanController::class, 'store'])->name('kendaraan.store.old'); // Route lama untuk backward compatibility
    });

    // ===========================================================
    // == ROUTE KHUSUS PENGELOLA RBAC & USER ==
    // ===========================================================
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::middleware(['permission:view_menu_pengguna|view_menu_pengguna_wp|view_menu_pengguna_stakeholder|view_menu_akses_group|view_menu_hak_akses|view_menu_cabang'])->group(function () {
            // User management — split WP vs Stakeholder
            Route::get('users/wajib-pajak', [UserController::class, 'indexWp'])->name('users.wp.index');
            Route::get('users/stakeholder', [UserController::class, 'indexStakeholder'])->name('users.stakeholder.index');
            Route::get('users/{user}/edit-wp', [UserController::class, 'editWp'])->name('users.editWp');
            Route::put('users/{user}/update-wp', [UserController::class, 'updateWp'])->name('users.updateWp');

            // Existing resource routes (create/edit/destroy for stakeholders)
            Route::resource('users', UserController::class);
            Route::resource('permissions', \App\Http\Controllers\Admin\PermissionController::class)->except(['show', 'edit', 'update']);
            Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class);
            Route::resource('cabangs', \App\Http\Controllers\Admin\CabangController::class);
        });

        // ===========================================================
        // == ROUTE PENGELOLA PENGAJUAN (Seluruh Instansi) ==
        // ===========================================================
        Route::middleware(['permission:view_menu_manajemen_pengajuan'])->group(function () {
            Route::get('/pengajuan', [AdminPengajuanController::class, 'index'])->name('pengajuan.index');
            Route::get('/pengajuan/{pengajuan}', [AdminPengajuanController::class, 'show'])->name('pengajuan.show');
            Route::get('/pengajuan/{pengajuan}/log/{logId}', [AdminPengajuanController::class, 'showLog'])->name('pengajuan.log.show');
            Route::delete('/pengajuan/{pengajuan}', [AdminPengajuanController::class, 'destroy'])->name('pengajuan.destroy')->middleware('permission:delete_pengajuan_publik');
            Route::patch('/pengajuan/{pengajuan}/batch-update', [AdminPengajuanController::class, 'batchUpdateKendaraanStatus'])->name('pengajuan.batchUpdate')->middleware('permission:approve_status_pengajuan');
            Route::post('/pengajuan/{pengajuan}/log', [AdminPengajuanController::class, 'storeLog'])->name('pengajuan.log.store');
            Route::get('/pengajuan/{pengajuan}/pilih-sk', [AdminPengajuanController::class, 'pilihSk'])->name('pengajuan.pilih_sk');
            Route::post('/pengajuan/{pengajuan}/generate-sk-regident', [AdminPengajuanController::class, 'generateSkRegident'])->name('pengajuan.generate_sk_regident');
            Route::post('/pengajuan/{pengajuan}/generate-sk-pembebasan', [AdminPengajuanController::class, 'generateSkPembebasan'])->name('pengajuan.generate_sk_pembebasan');
        });

        Route::post('/pengajuan/ajukan/{id}', [SPController::class, 'ajukan'])
            ->name('pengajuan.ajukan')
            ->middleware(['signed']);

        Route::post('/pengajuan/sp/terima/{surat}', [SPController::class, 'terima'])
            ->name('pengajuan.sp.terima')
            ->middleware(['signed']);
        Route::post('/pengajuan/sp/tolak/{surat}', [SPController::class, 'tolak'])
            ->name('pengajuan.sp.tolak')
            ->middleware(['signed']);

        Route::post('/pengajuan/buat-sk/{id}', [SKController::class, 'ajukan'])
            ->name('pengajuan.buat_sk')
            ->middleware(['signed']);
    });

    Route::prefix('kendaraan')->name('kendaraan.')->group(function () {
        Route::get('/{kendaraan}', [KendaraanController::class, 'show'])->name('show');
        Route::get('/{kendaraan}/edit', [KendaraanController::class, 'edit'])->name('edit');
        Route::patch('/{kendaraan}', [KendaraanController::class, 'update'])->name('update');
        Route::delete('/{kendaraan}', [KendaraanController::class, 'destroy'])->name('destroy');
    });

    // Generate PDF routes (shared between user and admin)
    Route::post('/pengajuan/generate-sk-polda', [AdminPengajuanController::class, 'generateSkPolda'])->name('pengajuan.generate_sk_polda');


    // API untuk mendapatkan tiket akses (UUID/Signature di URL)
    Route::post('/api/frame-access/{type}/{category}/{id}', [FrameController::class, 'requestAccess'])
        ->name('frame.access.request');

    // Centralized Render Route
    // Middleware 'signed' memastikan URL tidak bisa dimodifikasi/ditebak
    Route::get('/secure-frame/{type}/{category}/{id}', [FrameController::class, 'render'])
        ->middleware(['signed']) 
        ->name('frame.secure.render');

    // === TEMPORARY: Preview SK PDF (hapus setelah controller siap) ===
    Route::get('/preview-sk-regident', function () {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.sk_penghapusan_regident', [
            'kendaraan' => (object) [
                'nrkb' => 'AA 9660 QE',
                'merk_kendaraan' => 'VIAR',
                'tipe_kendaraan' => 'V 15 RL',
                'jenis_kendaraan' => 'SEPEDA MOTOR',
                'model_kendaraan' => 'RODA TIGA',
                'tahun_pembuatan' => '2015',
                'isi_silinder' => '150 CC',
                'nomor_rangka' => 'MGRVR15TAFL207980',
                'nomor_mesin' => 'YX161FMG15207805',
                'jenis_bahan_bakar' => 'BENSIN',
                'warna_tnkb' => 'MERAH',
                'nomor_bpkb' => 'M01679715',
                'pemilik' => (object) [
                    'nama_pemilik' => 'PEMERINTAH DESA GANDUWETAN',
                    'alamat_pemilik' => 'JL JUMO NO 03 KEC. NGADIREJO KAB. TEMANGGUNG',
                    'nik_pemilik' => '-',
                    'telp_pemilik' => '-',
                    'email_pemilik' => '-',
                ],
            ],
            'warna_kendaraan' => 'BIRU',
            'nomor_surat' => 'SKET/ 01 /VI/YAN.1/2025/Ditlantas',
            'nama_pembuat_pernyataan' => 'Dwiyanto Setyo Budi',
            'lokasi_pernyataan' => 'Temanggung',
            'tanggal_pernyataan' => '13 Mei 2024',
            'nama_pembuat_permohonan' => 'Dwiyanto Setyo Budi',
            'lokasi_permohonan' => 'Temanggung',
            'tanggal_permohonan' => '13 Mei 2024',
            'tempat_dikeluarkan' => 'Semarang',
            'tanggal_surat' => '30 Juni 2025',
            'jabatan_penandatangan' => 'DIREKTUR LALU LINTAS POLDA JAWA TENGAH',
        ]);
        return $pdf->setPaper('a4', 'portrait')->stream('SK_PENGHAPUSAN_REGIDENT.pdf');
    })->name('preview.sk.regident');

    Route::get('/preview-sk-polda', function () {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.sk_polda', [
            'data' => (object) [
                'nrkb' => 'AA 9660 QE',
                'nama' => 'PEMERINTAH DESA GANDUWETAN',
                'alamat' => 'JL. JUMO NO 03 KEL NGADIREJO KAB TEMANGGUNG',
                'jenis_model' => 'SEPEDA MOTOR/RODA TIGA',
                'merek_tipe' => 'VIAR/V15 RL',
                'tahun' => '2015',
                'isi_silinder' => '150 CC',
                'bahan_bakar' => 'BENSIN',
                'no_rangka' => 'MGRVR15TAFL207980',
                'no_mesin' => 'YX161FMG15207805',
                'warna' => 'BIRU',
                'no_bpkb' => 'M01679715',
            ],
            'nama_direktur' => 'M. PRATAMA, S.I.K., S.H., M.H.',
            'pangkat_direktur' => 'KOMISARIS BESAR POLISI NRP 68090397',
        ]);
        return $pdf->setPaper('a4', 'portrait')->stream('SK_POLDA.pdf');
    })->name('preview.sk.polda');
});