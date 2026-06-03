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
Route::get('/', [UmumController::class, 'index'])->name('home');
Route::get('/event', [UmumController::class, 'event'])->name('umum.event');
Route::get('/event/{id}', [UmumController::class, 'eventDetail'])->name('umum.event-detail');
Route::get('/berita', [UmumController::class, 'berita'])->name('umum.berita');
Route::get('/berita/{id}', [UmumController::class, 'beritaDetail'])->name('umum.berita-detail');
Route::get('/katalog', [UmumController::class, 'katalog'])->name('umum.katalog');
Route::get('/katalog/{id}', [UmumController::class, 'katalogDetail'])->name('umum.katalog-detail');
Route::get('/galeri', [UmumController::class, 'galeri'])->name('umum.galeri');
Route::get('/galeri/{id}', [UmumController::class, 'galeriDetail'])->name('umum.galeri-detail');
Route::get('/profil-sanggar', [UmumController::class, 'profil'])->name('umum.profil');

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
Route::prefix('admin')->middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('admin.dashboard');

    // Kelola Event
    Route::get('/kelola-event', [EventController::class, 'index'])->name('admin.kelola-event');
    Route::get('/kelola-event/tambah-event', [EventController::class, 'create'])->name('admin.tambah-event');
    Route::post('/kelola-event/store', [EventController::class, 'store'])->name('admin.store-event');
    Route::get('/event/edit/{id}', [EventController::class, 'edit'])->name('admin.edit-event');
    Route::put('/event/update/{id}', [EventController::class, 'update'])->name('admin.update-event');
    Route::delete('/event/hapus/{id}', [EventController::class, 'destroy'])->name('admin.hapus-event');

    // Kelola Berita 
    Route::get('/kelola-berita', [BeritaController::class, 'index'])->name('admin.kelola-berita');
    Route::get('/kelola-berita/tambah-berita', [BeritaController::class, 'create'])->name('admin.tambah-berita');
    Route::post('/kelola-berita/store', [BeritaController::class, 'store'])->name('admin.store-berita');
    Route::get('/berita/edit/{id}', [BeritaController::class, 'edit'])->name('admin.edit-berita');
    Route::put('/berita/update/{id}', [BeritaController::class, 'update'])->name('admin.update-berita');
    Route::delete('/berita/hapus/{id}', [BeritaController::class, 'destroy'])->name('admin.hapus-berita');

    // Kelola Profil Sanggar
    Route::get('/kelola-profil', [ProfilController::class, 'index'])->name('admin.kelola-profil');
    Route::get('/kelola-profil/edit-profil', [ProfilController::class, 'edit'])->name('admin.edit-profil');
    Route::put('/kelola-profil/update-profil', [ProfilController::class, 'update'])->name('admin.update-profil');
    Route::get('/kelola-profil/tambah-pengurus', [ProfilController::class, 'tambahPengurus'])->name('admin.tambah-pengurus');
    Route::post('/kelola-profil/store-pengurus', [ProfilController::class, 'storePengurus'])->name('admin.store-pengurus');
    Route::delete('/kelola-profil/hapus-pengurus/{id}', [ProfilController::class, 'hapusPengurus'])->name('admin.hapus-pengurus');
    Route::get('/kelola-profil/edit-pengurus/{id}', [ProfilController::class, 'editPengurus'])->name('admin.edit-pengurus');
    Route::put('/kelola-profil/update-pengurus/{id}', [ProfilController::class, 'updatePengurus'])->name('admin.update-pengurus');
    Route::get('/kelola-profil/tambah-pelatih', [ProfilController::class, 'tambahPelatih'])->name('admin.tambah-pelatih');
    Route::post('/kelola-profil/store-pelatih', [ProfilController::class, 'storePelatih'])->name('admin.store-pelatih');
    Route::delete('/kelola-profil/hapus-pelatih/{id}', [ProfilController::class, 'hapusPelatih'])->name('admin.hapus-pelatih');
    Route::get('/kelola-profil/edit-pelatih/{id}', [ProfilController::class, 'editPelatih'])->name('admin.edit-pelatih');
    Route::put('/kelola-profil/update-pelatih/{id}', [ProfilController::class, 'updatePelatih'])->name('admin.update-pelatih');

    // Pengaturan
    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('admin.pengaturan');
    Route::put('/pengaturan/update-profil', [PengaturanController::class, 'updateProfil'])->name('admin.pengaturan.update-profil');
    Route::put('/pengaturan/update-password', [PengaturanController::class, 'updatePassword'])->name('admin.pengaturan.update-password');

});

// =========================================================================
// ERROR POPUP ROUTES (bisa diakses tanpa login)
// =========================================================================
Route::get('/error/akses', function () {
    return view('error.akses');
})->name('error.akses');

Route::get('/error/server', function () {
    return view('error.server');
})->name('error.server');

Route::get('/error/path', function () {
    return view('error.path');
})->name('error.path');

// =========================================================================
// 4. ROUTE UNTUK SUPER ADMIN
// =========================================================================
Route::prefix('superadmin')->middleware(['superadmin.auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardSuperadminController::class, 'index'])->name('superadmin.dashboard');

    // Kelola Admin
    Route::get('/kelola-admin', [AdminController::class, 'index'])->name('superadmin.kelola-admin');
    Route::get('/kelola-admin/tambah-admin', [AdminController::class, 'create'])->name('superadmin.tambah-admin');
    Route::post('/kelola-admin/store', [AdminController::class, 'store'])->name('superadmin.store-admin');
    Route::get('/admin/edit/{id}', [AdminController::class, 'edit'])->name('superadmin.edit-admin');
    Route::put('/admin/update/{id}', [AdminController::class, 'update'])->name('superadmin.update-admin');
    Route::delete('/admin/hapus/{id}', [AdminController::class, 'destroy'])->name('superadmin.hapus-admin');

    // Kelola Katalog
    Route::get('/kelola-katalog', [KatalogController::class, 'index'])->name('superadmin.kelola-katalog');
    Route::get('/kelola-katalog/tambah-katalog', [KatalogController::class, 'create'])->name('superadmin.tambah-katalog');
    Route::post('/kelola-katalog/store', [KatalogController::class, 'store'])->name('superadmin.store-katalog');
    Route::get('/katalog/edit/{id}', [KatalogController::class, 'edit'])->name('superadmin.edit-katalog');
    Route::put('/katalog/update/{id}', [KatalogController::class, 'update'])->name('superadmin.update-katalog');
    Route::delete('/katalog/hapus/{id}', [KatalogController::class, 'destroy'])->name('superadmin.hapus-katalog');

    // Kelola Event
    Route::get('/kelola-event', [EventController::class, 'index'])->name('superadmin.kelola-event');
    Route::get('/kelola-event/tambah-event', [EventController::class, 'create'])->name('superadmin.tambah-event');
    Route::post('/kelola-event/store', [EventController::class, 'store'])->name('superadmin.store-event');
    Route::get('/event/edit/{id}', [EventController::class, 'edit'])->name('superadmin.edit-event');
    Route::put('/event/update/{id}', [EventController::class, 'update'])->name('superadmin.update-event');
    Route::delete('/event/hapus/{id}', [EventController::class, 'destroy'])->name('superadmin.hapus-event');

    // Kelola Berita 
    Route::get('/kelola-berita', [BeritaController::class, 'index'])->name('superadmin.kelola-berita');
    Route::get('/kelola-berita/tambah-berita', [BeritaController::class, 'create'])->name('superadmin.tambah-berita');
    Route::post('/kelola-berita/store', [BeritaController::class, 'store'])->name('superadmin.store-berita');
    Route::get('/berita/edit/{id}', [BeritaController::class, 'edit'])->name('superadmin.edit-berita');
    Route::put('/berita/update/{id}', [BeritaController::class, 'update'])->name('superadmin.update-berita');
    Route::delete('/berita/hapus/{id}', [BeritaController::class, 'destroy'])->name('superadmin.hapus-berita');

    // Kelola Profil Sanggar
    Route::get('/kelola-profil', [ProfilController::class, 'index'])->name('superadmin.kelola-profil');
    Route::get('/kelola-profil/edit-profil', [ProfilController::class, 'edit'])->name('superadmin.edit-profil');
    Route::put('/kelola-profil/update-profil', [ProfilController::class, 'update'])->name('superadmin.update-profil');
    Route::get('/kelola-profil/tambah-pengurus', [ProfilController::class, 'tambahPengurus'])->name('superadmin.tambah-pengurus');
    Route::post('/kelola-profil/store-pengurus', [ProfilController::class, 'storePengurus'])->name('superadmin.store-pengurus');
    Route::delete('/kelola-profil/hapus-pengurus/{id}', [ProfilController::class, 'hapusPengurus'])->name('superadmin.hapus-pengurus');
    Route::get('/kelola-profil/edit-pengurus/{id}', [ProfilController::class, 'editPengurus'])->name('superadmin.edit-pengurus');
    Route::put('/kelola-profil/update-pengurus/{id}', [ProfilController::class, 'updatePengurus'])->name('superadmin.update-pengurus');
    Route::get('/kelola-profil/tambah-pelatih', [ProfilController::class, 'tambahPelatih'])->name('superadmin.tambah-pelatih');
    Route::post('/kelola-profil/store-pelatih', [ProfilController::class, 'storePelatih'])->name('superadmin.store-pelatih');
    Route::delete('/kelola-profil/hapus-pelatih/{id}', [ProfilController::class, 'hapusPelatih'])->name('superadmin.hapus-pelatih');
    Route::get('/kelola-profil/edit-pelatih/{id}', [ProfilController::class, 'editPelatih'])->name('superadmin.edit-pelatih');
    Route::put('/kelola-profil/update-pelatih/{id}', [ProfilController::class, 'updatePelatih'])->name('superadmin.update-pelatih');

    // Pengaturan
    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('superadmin.pengaturan');
    Route::put('/pengaturan/update-profil', [PengaturanController::class, 'updateProfil'])->name('superadmin.pengaturan.update-profil');
    Route::put('/pengaturan/update-password', [PengaturanController::class, 'updatePassword'])->name('superadmin.pengaturan.update-password');

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