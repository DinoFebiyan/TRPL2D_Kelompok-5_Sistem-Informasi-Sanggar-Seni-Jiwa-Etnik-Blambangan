<?php

use App\Http\Controllers\ProfileController;
use App\http\Controllers\AdminController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\LogAktivitasController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\DashboardSuperadminController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\UmumController;
use Illuminate\Support\Facades\Route;

// =========================================================================
// 1. HALAMAN UTAMA & PUBLIK (Folder: views/umum)
// =========================================================================
Route::get('/', [LandingPageController::class, 'index'])->name('home');
Route::get('/event', [UmumController::class, 'event'])->name('umum.event');
Route::get('/event/{id}', [UmumController::class, 'eventDetail'])->name('umum.event-detail');
Route::get('/berita', [UmumController::class, 'berita'])->name('umum.berita');
Route::get('/berita/{id}', [UmumController::class, 'beritaDetail'])->name('umum.berita-detail');
Route::get('/katalog', [UmumController::class, 'katalog'])->name('umum.katalog');
Route::get('/katalog/{id}', [UmumController::class, 'katalogDetail'])->name('umum.katalog-detail');
Route::get('/galeri', [UmumController::class, 'galeri'])->name('umum.galeri');
Route::get('/galeri/{id}', [UmumController::class, 'galeriDetail'])->name('umum.galeri-detail');
Route::get('/profil', [UmumController::class, 'profil'])->name('umum.profil');

// =========================================================================
// 2. AUTH DEFAULT & OTP (Folder: views/auth)
// =========================================================================
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/auth/verifikasi_otp', function () {
    return view('auth.verifikasi_otp');
})->name('verifikasi.otp');


// =========================================================================
// 3. ROUTE UNTUK ADMIN BIASA
// =========================================================================


// =========================================================================
// 4. ROUTE UNTUK SUPER ADMIN
// =========================================================================
Route::prefix('superadmin')->middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardSuperadminController::class, 'index'])->name('superadmin.dashboard');

    // Kelola Admin
    Route::get('/kelola-admin', [AdminController::class, 'index'])->name('superadmin.kelola-admin');
    Route::get('/kelola-admin/tambah-admin', [AdminController::class, 'create'])->name('superadmin.tambah-admin');
    Route::post('/kelola-admin/store', [AdminController::class, 'store'])->name('superadmin.store-admin');
    Route::get('/admin/edit/{id}', [AdminController::class, 'edit'])->name('superadmin.edit-admin');
    Route::put('/admin/update/{id}', [AdminController::class, 'update'])->name('superadmin.update-admin');
    Route::delete('/admin/delete/{id}', [AdminController::class, 'destroy'])->name('superadmin.hapus-admin');

    // Kelola Katalog
    Route::get('/kelola-katalog', [KatalogController::class, 'index'])->name('superadmin.kelola-katalog');

    // Kelola Event
    Route::get('/kelola-event', [EventController::class, 'index'])->name('superadmin.kelola-event');

    // Kelola Berita 
    Route::get('/kelola-berita', [BeritaController::class, 'index'])->name('superadmin.kelola-berita');
    Route::get('/kelola-berita/tambah-berita', [BeritaController::class, 'create'])->name('superadmin.tambah-berita');
    // Route::post('/kelola-berita/store-berita', [BeritaController::class, 'store'])->name('superadmin.store-berita');
    // Route::get('/berita/edit/{id}', [BeritaController::class, 'edit'])->name('superadmin.berita.edit');
    // Route::put('/berita/update/{id}', [BeritaController::class, 'update'])->name('superadmin.berita.update');
    // Route::delete('/berita/delete/{id}', [BeritaController::class, 'destroy'])->name('superadmin.berita.delete');

    // Kelola Profil Sanggar
    Route::get('/kelola-profil', [ProfilController::class, 'index'])->name('superadmin.kelola-profil');
    Route::get('/kelola-profil/edit-profil', [ProfilController::class, 'edit'])->name('superadmin.edit-profil');
    // Route::put('/profil/update', [ProfilController::class, 'updateProfil'])->name('superadmin.profil.update'); 
    Route::get('/kelola-profil/tambah-pengurus', [ProfilController::class, 'tambahPengurus'])->name('superadmin.tambah-pengurus');
    Route::get('/kelola-profil/tambah-pelatih', [ProfilController::class, 'tambahPelatih'])->name('superadmin.tambah-pelatih');

    // Pengaturan
    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('superadmin.pengaturan');
    Route::put('/pengaturan/update-akun', [PengaturanController::class, 'updateProfil'])->name('superadmin.update-akun');
    Route::put('/pengaturan/update-password', [PengaturanController::class, 'updatePassword'])->name('superadmin.update-password');

    // Log Aktivitas 
    Route::get('/log-aktivitas', [LogAktivitasController::class, 'index'])->name('superadmin.log-aktivitas');
});

// =========================================================================
// BAWAAN BREEZE (JANGAN DIHAPUS)
// =========================================================================
Route::middleware(['auth'])->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/edit', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/edit', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';