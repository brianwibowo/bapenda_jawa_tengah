<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\Admin\PengajuanController as AdminPengajuanController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\KendaraanController; // <-- CONTROLLER BARU
use App\Http\Controllers\FrameController; // <-- CONTROLLER BARU
use App\Http\Controllers\SkController; // <-- CONTROLLER BARU

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
    // == ROUTE KHUSUS UNTUK ROLE 'Wajib Pajak' ===
    // ===========================================================
    Route::middleware(['role:wp'])->group(function () {

        // Melihat daftar miliknya
        Route::get('/pengajuan-saya', [PengajuanController::class, 'index'])
            ->name('pengajuan.index')
            ->middleware('permission:view_own_pengajuan');

        // Form Bikin Baru
        Route::get('/pengajuan/buat', [PengajuanController::class, 'create'])
            ->name('pengajuan.create')
            ->middleware('permission:create_pengajuan');

        // Proses Create
        Route::post('/pengajuan', [PengajuanController::class, 'store'])
            ->name('pengajuan.store')
            ->middleware('permission:store_pengajuan');

        // Buka Detail / View (Harus punya izin view)
        Route::get('/pengajuan/{pengajuan}', [PengajuanController::class, 'show'])
            ->name('pengajuan.show')
            ->middleware('permission:view_own_pengajuan');

        Route::delete('/pengajuan/{pengajuan}', [PengajuanController::class, 'destroy'])->name('pengajuan.destroy');
        Route::get('/pengajuan/{pengajuan}/log/{logId}', [PengajuanController::class, 'showLog'])->name('pengajuan.log.show');
        Route::post('/pengajuan/{pengajuan}/log', [PengajuanController::class, 'storeLog'])->name('pengajuan.log.store');

        Route::prefix('kendaraans')->group(function () {
            Route::post('/', [PengajuanController::class, 'storeKendaraan'])->name('kendaraan.store');
            Route::get('/{pengajuan}/tambah-kendaraan', [KendaraanController::class, 'create'])->name('kendaraan.create');
            Route::post('/{pengajuan}/simpan-kendaraan-lama', [KendaraanController::class, 'store'])->name('kendaraan.store.old'); // Route lama untuk backward compatibility
        });
    });

    // ===========================================================
    // == ROUTE KHUSUS UNTUK ROLE 'ADMIN' & 'SUPERADMIN' ==
    // ===========================================================
    Route::prefix('admin')->name('admin.')->group(function () {

        // --- Grup Pengelola RBAC & USER (Hanya Admin / Superadmin) ---
        Route::middleware(['role:admin|superadmin'])->group(function () {
            Route::resource('users', UserController::class);
            Route::resource('permissions', \App\Http\Controllers\Admin\PermissionController::class)->except(['show', 'edit', 'update']);
            Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class);
        });

        // --- Grup Pengelola PENGAJUAN (Admin / Superadmin / Akses Pengajuan) ---
        Route::middleware(['role:admin|superadmin|Pengajuan'])->group(function () {
            Route::get('/pengajuan', [AdminPengajuanController::class, 'index'])->name('pengajuan.index');
            Route::get('/pengajuan/{pengajuan}', [AdminPengajuanController::class, 'show'])->name('pengajuan.show');
            Route::get('/pengajuan/{pengajuan}/log/{logId}', [AdminPengajuanController::class, 'showLog'])->name('pengajuan.log.show');
            Route::delete('/pengajuan/{pengajuan}', [AdminPengajuanController::class, 'destroy'])->name('pengajuan.destroy');
            Route::patch('/pengajuan/{pengajuan}/batch-update', [AdminPengajuanController::class, 'batchUpdateKendaraanStatus'])->name('pengajuan.batchUpdate');
            Route::post('/pengajuan/{pengajuan}/log', [AdminPengajuanController::class, 'storeLog'])->name('pengajuan.log.store');
        });
    });


    // =================================================================
    // == ROUTE SHARED (PENULIS & ADMIN) UNTUK KENDARAAN INDIVIDUAL ==
    // =================================================================
    // Keamanan ditangani di dalam Controller
    Route::prefix('kendaraan')->name('kendaraan.')->group(function () {

        // (Penulis & Admin) Menampilkan detail read-only 1 kendaraan
        Route::get('/{kendaraan}', [KendaraanController::class, 'show'])->name('show');

        // (Penulis) Menampilkan form edit
        Route::get('/{kendaraan}/edit', [KendaraanController::class, 'edit'])->name('edit');

        // (Penulis) Menyimpan data editan
        Route::patch('/{kendaraan}', [KendaraanController::class, 'update'])->name('update');

        // (Penulis & Admin) Menghapus 1 kendaraan
        Route::delete('/{kendaraan}', [KendaraanController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('sk')->name('sk.')->group(function () {
        Route::get('/', [SkController::class, 'index'])
            ->name('index')
            ->middleware('permission:view_own_sk');
        
        Route::get('/buat', [SkController::class, 'create'])
            ->name('create')
            ->middleware('permission:create_sk');
    });

    //ROUTE KHUSUS UNTUK ROLE 'POLDA'
   
    Route::prefix('polda')->name('polda.')->middleware(['role:polda'])->group(function () {
        // Lihat semua pengajuan (seluruh wilayah)
        Route::get('/pengajuan', [AdminPengajuanController::class, 'index'])->name('pengajuan.index');
        Route::get('/pengajuan/{pengajuan}', [AdminPengajuanController::class, 'show'])->name('pengajuan.show');

        // Flow: buat & lihat surat PDF pengajuan ke Bapenda / JR (preview menggunakan same pdf generator)
        Route::get('/pengajuan/{pengajuan}/surat/pengajuan/bapenda', [PdfGeneratorController::class, 'finalPJN'])->name('pengajuan.surat.pengajuan.bapenda');
        Route::get('/pengajuan/{pengajuan}/surat/pengajuan/jr', [PdfGeneratorController::class, 'finalPJN'])->name('pengajuan.surat.pengajuan.jr');

        // Buat & lihat surat keputusan/SK (via log builder)
        Route::post('/pengajuan/{pengajuan}/log', [AdminPengajuanController::class, 'storeLog'])->name('pengajuan.log.store');
        Route::get('/pengajuan/{pengajuan}/log/{logId}', [AdminPengajuanController::class, 'showLog'])->name('pengajuan.log.show');

        // Lihat surat balasan / SK dari Bapenda & JR (di log)
        // (Ambil dari detail log yang sudah diattach file di storeLog)
        Route::get('/pengajuan/{pengajuan}/log', [AdminPengajuanController::class, 'show'])->name('pengajuan.logs');
    });

    //ROUTE KHUSUS UNTUK ROLE 'BAPENDA'
    Route::prefix('bapenda')->name('bapenda.')->middleware(['role:bapenda'])->group(function () {
        Route::get('/pengajuan', [AdminPengajuanController::class, 'index'])->name('pengajuan.index');
        Route::get('/pengajuan/{pengajuan}', [AdminPengajuanController::class, 'show'])->name('pengajuan.show');

        Route::get('/pengajuan/{pengajuan}/surat/pengajuan/polda', [PdfGeneratorController::class, 'finalPJN'])->name('pengajuan.surat.pengajuan.polda');
        Route::get('/pengajuan/{pengajuan}/surat/sk', [PdfGeneratorController::class, 'finalPJN'])->name('pengajuan.surat.sk');

        Route::post('/pengajuan/{pengajuan}/log', [AdminPengajuanController::class, 'storeLog'])->name('pengajuan.log.store');
        Route::get('/pengajuan/{pengajuan}/log/{logId}', [AdminPengajuanController::class, 'showLog'])->name('pengajuan.log.show');
        Route::get('/pengajuan/{pengajuan}/log', [AdminPengajuanController::class, 'show'])->name('pengajuan.logs');
    });

    //ROUTE KHUSUS UNTUK ROLE 'JR'
    Route::prefix('jr')->name('jr.')->middleware(['role:jr'])->group(function () {
        Route::get('/pengajuan', [AdminPengajuanController::class, 'index'])->name('pengajuan.index');
        Route::get('/pengajuan/{pengajuan}', [AdminPengajuanController::class, 'show'])->name('pengajuan.show');

        Route::get('/pengajuan/{pengajuan}/surat/pengajuan/polda', [PdfGeneratorController::class, 'finalPJN'])->name('pengajuan.surat.pengajuan.polda');
        Route::get('/pengajuan/{pengajuan}/surat/sk', [PdfGeneratorController::class, 'finalPJN'])->name('pengajuan.surat.sk');

        Route::post('/pengajuan/{pengajuan}/log', [AdminPengajuanController::class, 'storeLog'])->name('pengajuan.log.store');
        Route::get('/pengajuan/{pengajuan}/log/{logId}', [AdminPengajuanController::class, 'showLog'])->name('pengajuan.log.show');
        Route::get('/pengajuan/{pengajuan}/log', [AdminPengajuanController::class, 'show'])->name('pengajuan.logs');
    });

    //ROUTE KHUSUS UNTUK ROLE 'SAMSAT'

    Route::prefix('samsat')->name('samsat.')->middleware(['role:samsat'])->group(function () {
        // Lihat semua pengajuan (seluruh wilayah)
        Route::get('/pengajuan', [AdminPengajuanController::class, 'index'])->name('pengajuan.index');
        Route::get('/pengajuan/{pengajuan}', [AdminPengajuanController::class, 'show'])->name('pengajuan.show');

        // Aksi revisi / diterima
        Route::post('/pengajuan/{pengajuan}/log', [AdminPengajuanController::class, 'storeLog'])->name('pengajuan.log.store');
        Route::get('/pengajuan/{pengajuan}/log/{logId}', [AdminPengajuanController::class, 'showLog'])->name('pengajuan.log.show');
        Route::get('/pengajuan/{pengajuan}/log', [AdminPengajuanController::class, 'show'])->name('pengajuan.logs');

        // Surat pengajuan ke Polda + dokumen surat / balasan dari Polda, Bapenda & JR
        Route::get('/pengajuan/{pengajuan}/surat/pengajuan/polda', [PdfGeneratorController::class, 'finalPJN'])->name('pengajuan.surat.pengajuan.polda');
        Route::get('/pengajuan/{pengajuan}/surat/pengajuan/bapenda', [PdfGeneratorController::class, 'finalPJN'])->name('pengajuan.surat.pengajuan.bapenda');
        Route::get('/pengajuan/{pengajuan}/surat/pengajuan/jr', [PdfGeneratorController::class, 'finalPJN'])->name('pengajuan.surat.pengajuan.jr');
        Route::get('/pengajuan/{pengajuan}/surat/sk', [PdfGeneratorController::class, 'finalPJN'])->name('pengajuan.surat.sk');
    });

});