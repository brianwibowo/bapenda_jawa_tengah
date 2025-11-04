<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\Admin\PengajuanController as AdminPengajuanController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\KendaraanController; // <-- CONTROLLER BARU

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
require __DIR__.'/auth.php';


// == GRUP UNTUK SEMUA ROUTE YANG MEMBUTUHKAN LOGIN ==
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard (Bisa diakses semua role yang sudah login)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Management (Bawaan Breeze/Jetstream)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Route untuk "pos pengecekan" setelah login
    Route::get('/redirect', [RedirectController::class, 'handle'])->name('redirect.after.login');

    
    // =======================================================
    // == ROUTE KHUSUS UNTUK ROLE 'PENULIS' (DIROMBAK TOTAL) ==
    // =======================================================
    Route::middleware(['role:penulis'])->group(function () {
        
        // --- Grup untuk mengelola "Bundel" Pengajuan ---
        // 'prefix' dan 'name' agar rapi (cth: route('pengajuan.index'))
        Route::prefix('pengajuan-saya')->name('pengajuan.')->group(function () {
            
            // [Langkah 2 UX] Menampilkan halaman daftar bundel pengajuan
            // GET pengajuan-saya/
            Route::get('/', [PengajuanController::class, 'index'])->name('index'); 

            // [Langkah 1 UX] Aksi dari tombol "Buat Nomor Pengajuan Baru"
            // POST pengajuan-saya/
            Route::post('/', [PengajuanController::class, 'store'])->name('store'); 

            // [Langkah 3 UX] Menampilkan halaman detail bundel (tabel kendaraan)
            // GET pengajuan-saya/{pengajuan}
            Route::get('/{pengajuan}', [PengajuanController::class, 'show'])->name('show');
            
            // (Opsional) Hapus bundel jika masih draft
            Route::delete('/{pengajuan}', [PengajuanController::class, 'destroy'])->name('destroy');
            
            // [Langkah 6 UX] Halaman form untuk "+ Tambah Kendaraan"
            // GET pengajuan-saya/{pengajuan}/tambah-kendaraan
            Route::get('/{pengajuan}/tambah-kendaraan', [KendaraanController::class, 'create'])->name('kendaraan.create');
            
            // [Langkah 8 UX] Aksi submit form tambah kendaraan
            // POST pengajuan-saya/{pengajuan}/simpan-kendaraan
            Route::post('/{pengajuan}/simpan-kendaraan', [KendaraanController::class, 'store'])->name('kendaraan.store');
        });

        // --- Grup untuk mengelola "Kendaraan" individual ---
        // (Untuk Edit/Hapus kendaraan dari tabel di halaman 'show')
        Route::prefix('kendaraan')->name('kendaraan.')->group(function () {
            
            // Halaman form Edit Kendaraan
            // GET kendaraan/{kendaraan}/edit
            Route::get('/{kendaraan}/edit', [KendaraanController::class, 'edit'])->name('edit');
            
            // Aksi submit form Edit Kendaraan
            // PATCH kendaraan/{kendaraan}
            Route::patch('/{kendaraan}', [KendaraanController::class, 'update'])->name('update');
            
            // Aksi tombol Hapus Kendaraan
            // DELETE kendaraan/{kendaraan}
            Route::delete('/{kendaraan}', [KendaraanController::class, 'destroy'])->name('destroy');
            Route::get('/{kendaraan}', [KendaraanController::class, 'show'])->name('show');
        });
    });


    // ===========================================================
    // == ROUTE KHUSUS UNTUK ROLE 'ADMIN' & 'SUPERADMIN' (Update) ==
    // ===========================================================
    Route::middleware(['role:admin|superadmin'])->prefix('admin')->name('admin.')->group(function () {
        
        // User Management (Tetap sama)
        Route::resource('users', UserController::class);

        // Pengajuan Management (Dibersihkan)
        Route::get('/pengajuan', [AdminPengajuanController::class, 'index'])->name('pengajuan.index'); // Daftar bundel
        Route::get('/pengajuan/{pengajuan}', [AdminPengajuanController::class, 'show'])->name('pengajuan.show'); // Detail bundel (tab kendaraan)
        Route::delete('/pengajuan/{pengajuan}', [AdminPengajuanController::class, 'destroy'])->name('pengajuan.destroy'); // Hapus bundel
        
        // Route untuk Pop-up Aksi (store log & update status)
        Route::post('/pengajuan/{pengajuan}/log', [AdminPengajuanController::class, 'storeLogAndUpdateStatus'])->name('pengajuan.storeLog');
        
        // HAPUS Route::patch(...'updateStatus') karena digantikan 'storeLog'
    });
    
});