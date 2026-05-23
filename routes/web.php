<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\KatalogController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\DashboardSuperadminController;
use Illuminate\Support\Facades\Route;

// =========================================================================
// 1. HALAMAN UTAMA & PUBLIK (Folder: views/umum)
// =========================================================================
Route::get('/', [LandingPageController::class, 'index'])->name('home');
    Route::get('/event', [UmumController::class, 'event'])->name('event.index');
Route::get('/event/{id}', [UmumController::class, 'eventDetail'])->name('event.detail');
Route::get('/berita', [UmumController::class, 'berita'])->name('berita.index');
Route::get('/berita/{id}', [UmumController::class, 'beritaDetail'])->name('berita.detail');
Route::get('/katalog', [KatalogController::class, 'katalog'])->name('katalog.index');
Route::get('/katalog/{id}', [KatalogController::class, 'katalogDetail'])->name('katalog.detail');
Route::get('/galeri', [UmumController::class, 'galeri'])->name('galeri.index');
Route::get('/galeri/{id}', [UmumController::class, 'galeriDetail'])->name('galeri.detail');
Route::get('/profil', [UmumController::class, 'profil'])->name('profil.sanggar');

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
    Route::get('/admin', [SuperAdminController::class, 'kelolaAdmin'])->name('superadmin.kelola-admin');

    // Kelola Katalog
    Route::get('/katalog', [SuperAdminController::class, 'kelolaKatalog'])->name('superadmin.kelola-katalog');

    // Kelola Event
    Route::get('/event', [SuperAdminController::class, 'kelolaEvent'])->name('superadmin.kelola-event');

    // Kelola Berita 
    Route::get('/kelola-berita', [SuperAdminController::class, 'kelolaBerita'])->name('superadmin.kelola-berita');
    Route::get('/tambah-berita', [SuperAdminController::class, 'tambahBerita'])->name('superadmin.tambah-berita');
    Route::post('/store-berita', [SuperAdminController::class, 'storeBerita'])->name('superadmin.store-berita');
    Route::get('/berita/edit/{id}', [SuperAdminController::class, 'editBerita'])->name('superadmin.berita.edit');
    Route::put('/berita/update/{id}', [SuperAdminController::class, 'updateBerita'])->name('superadmin.berita.update');
    Route::delete('/berita/delete/{id}', [SuperAdminController::class, 'deleteBerita'])->name('superadmin.berita.delete');

    // Kelola Profil Sanggar
    Route::get('/profil', [SuperAdminController::class, 'kelolaProfil'])->name('superadmin.kelola-profil');
    Route::get('/edit-profil', [SuperAdminController::class, 'editProfil'])->name('superadmin.edit-profil');
    Route::put('/profil/update', [SuperAdminController::class, 'updateProfil'])->name('superadmin.profil.update'); 
    Route::get('/tambah-pengurus', [SuperAdminController::class, 'tambahPengurus'])->name('superadmin.tambah-pengurus');
    Route::get('/tambah-pelatih', [SuperAdminController::class, 'tambahPelatih'])->name('superadmin.tambah-pelatih');

    // Pengaturan
    Route::get('/pengaturan', [SuperAdminController::class, 'pengaturan'])->name('superadmin.pengaturan.index');
    Route::put('/pengaturan/update', [SuperAdminController::class, 'updateProfil'])->name('superadmin.pengaturan.update');
    Route::put('/pengaturan/update-password', [SuperAdminController::class, 'updatePassword'])->name('superadmin.pengaturan.password');

    // Log Aktivitas 
    Route::get('/log-aktivitas', [SuperAdminController::class, 'logAktivitas'])->name('superadmin.log-aktivitas');
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