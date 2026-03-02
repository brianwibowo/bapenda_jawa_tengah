<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\Admin\PengajuanController as AdminPengajuanController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\KendaraanController; // <-- CONTROLLER BARU
use App\Http\Controllers\PdfGeneratorController;

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


    // =======================================================
    // == ROUTE KHUSUS UNTUK ROLE 'PENULIS' ==
    // =======================================================
    Route::middleware(['role:penulis'])->group(function () {

        // --- Grup untuk mengelola "Bundel" Pengajuan ---
        Route::prefix('pengajuan-saya')->name('pengajuan.')->group(function () {
            Route::get('/', [PengajuanController::class, 'index'])->name('index');
            Route::get('/buat', [PengajuanController::class, 'create'])->name('create'); // Halaman Buat Pengajuan baru
            Route::post('/simpan-pengajuan', [PengajuanController::class, 'store'])->name('store'); // Simpan pengajuan dengan semua kendaraan
            Route::post('/simpan-kendaraan', [PengajuanController::class, 'storeKendaraan'])->name('kendaraan.store'); // Simpan satu kendaraan
            Route::get('/{pengajuan}', [PengajuanController::class, 'show'])->name('show');
            Route::post('/{pengajuan}/log', [PengajuanController::class, 'storeLog'])->name('log.store');
            Route::get('/{pengajuan}/log/{logId}', [PengajuanController::class, 'showLog'])->name('log.show');
            Route::delete('/{pengajuan}', [PengajuanController::class, 'destroy'])->name('destroy');
            Route::get('/{pengajuan}/tambah-kendaraan', [KendaraanController::class, 'create'])->name('kendaraan.create');
            Route::post('/{pengajuan}/simpan-kendaraan-lama', [KendaraanController::class, 'store'])->name('kendaraan.store.old'); // Route lama untuk backward compatibility
        });

        // --- Grup 'kendaraan' DIPINDAHKAN KELUAR dari role:penulis ---
    });

    // ===========================================================
    // == ROUTE KHUSUS UNTUK ROLE 'ADMIN' & 'SUPERADMIN' ==
    // ===========================================================
    Route::middleware(['role:admin|superadmin'])->prefix('admin')->name('admin.')->group(function () {

        Route::resource('users', UserController::class);

        Route::get('/pengajuan', [AdminPengajuanController::class, 'index'])->name('pengajuan.index');
        Route::get('/pengajuan/{pengajuan}', [AdminPengajuanController::class, 'show'])->name('pengajuan.show');
        Route::get('/pengajuan/{pengajuan}/log/{logId}', [AdminPengajuanController::class, 'showLog'])->name('pengajuan.log.show');
        Route::delete('/pengajuan/{pengajuan}', [AdminPengajuanController::class, 'destroy'])->name('pengajuan.destroy');
        Route::patch('/pengajuan/{pengajuan}/batch-update', [AdminPengajuanController::class, 'batchUpdateKendaraanStatus'])->name('pengajuan.batchUpdate');
        Route::post('/pengajuan/{pengajuan}/log', [AdminPengajuanController::class, 'storeLog'])->name('pengajuan.log.store');
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
    
    Route::get('/pdf/view/{id}', [PdfGeneratorController::class, 'finalPJN'])->name('pdf.view');

});