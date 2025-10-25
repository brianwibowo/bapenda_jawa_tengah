<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\Admin\PengajuanController as AdminPengajuanController;
use App\Http\Controllers\RedirectController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sini Anda dapat mendaftarkan rute web untuk aplikasi Anda. Rute-rute
| ini dimuat oleh RouteServiceProvider dan semuanya akan
| ditugaskan ke grup middleware "web". Buat sesuatu yang hebat!
|
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

    // == ROUTE KHUSUS UNTUK ROLE 'PENULIS' ==
    Route::middleware(['role:penulis'])->group(function () {
        Route::get('/pengajuan/saya', [PengajuanController::class, 'index'])->name('pengajuan.index');
        Route::get('/pengajuan/buat', [PengajuanController::class, 'create'])->name('pengajuan.create');
        Route::post('/pengajuan', [PengajuanController::class, 'store'])->name('pengajuan.store');
        Route::get('/pengajuan/{pengajuan}', [PengajuanController::class, 'show'])->name('pengajuan.show'); 
    });

    Route::middleware(['role:admin|superadmin'])->prefix('admin')->name('admin.')->group(function () {
        
        // User Management
        Route::resource('users', UserController::class);

        // Pengajuan Management
        Route::get('/pengajuan', [AdminPengajuanController::class, 'index'])->name('pengajuan.index');
        Route::get('/pengajuan/{pengajuan}', [AdminPengajuanController::class, 'show'])->name('pengajuan.show');
        Route::patch('/pengajuan/{pengajuan}/status', [AdminPengajuanController::class, 'updateStatus'])->name('pengajuan.updateStatus');
        Route::delete('/pengajuan/{pengajuan}', [AdminPengajuanController::class, 'destroy'])->name('pengajuan.destroy');
        Route::post('/pengajuan/{pengajuan}/log', [AdminPengajuanController::class, 'storeLogAndUpdateStatus'])->name('pengajuan.storeLog');
    });

    Route::get('/redirect', [RedirectController::class, 'handle'])->name('redirect.after.login');
    
});