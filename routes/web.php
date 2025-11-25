<?php

use Illuminate\Support\Facades\Route;
// Controller
use App\Http\Controllers\LandingController; // Pastikan Controller ini sudah dibuat
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\RakbukuController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ====================================================
// 1. HALAMAN PUBLIK (LANDING PAGE)
// ====================================================
// Ini adalah halaman pertama yang dilihat semua orang (Admin/User/Tamu)
Route::get('/', [LandingController::class, 'index'])->name('landing');


// ====================================================
// 2. OTENTIKASI (LOGIN & LOGOUT)
// ====================================================
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'authenticate'])->name('login.authenticate');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');


// ====================================================
// 3. RUTE KHUSUS ADMIN (DILINDUNGI MIDDLEWARE)
// ====================================================
// Hanya user yang login DAN punya role 'admin' yang bisa masuk sini
Route::middleware(['auth', 'is_admin'])->group(function () {

    // Dashboard Admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Manajemen Member
    Route::prefix('member')->name('member.')->group(function() {
        Route::get('/', [MemberController::class, 'index'])->name('index');
        Route::get('/create', [MemberController::class, 'create'])->name('create');
        Route::post('/', [MemberController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [MemberController::class, 'edit'])->name('edit');
        Route::put('/{id}', [MemberController::class, 'update'])->name('update');
        Route::delete('/{id}', [MemberController::class, 'destroy'])->name('destroy');
    });

    // Manajemen Buku
    Route::prefix('books')->name('books.')->group(function() {
        Route::get('/', [BookController::class, 'index'])->name('index');
        Route::get('/create', [BookController::class, 'create'])->name('create');
        Route::post('/', [BookController::class, 'store'])->name('store');
        Route::get('/{id}/update', [BookController::class, 'getBook'])->name('getBook'); // Sedikit fix typo url
        Route::put('/{book}', [BookController::class, 'update'])->name('update'); // Perbaiki penamaan route agar konsisten
        Route::delete('/{id}', [BookController::class, 'destroy'])->name('destroy');
    });
    
    // Detail Buku (Bisa diakses Admin, bisa juga dipindah ke publik jika perlu)
    Route::get('/book/{id}/', [BookController::class, 'showDetail'])->name('books.showDetail');

    // Rak Buku
    Route::prefix('rak')->name('Rak.')->group(function() {
        Route::get('/', [RakbukuController::class, 'index'])->name('showdata');
        Route::get('/create', [RakbukuController::class, 'create'])->name('createRak');
        Route::post('/create', [RakbukuController::class, 'store'])->name('storeRak');
    });
    // Route khusus resource rack (update/delete biasanya butuh ID)
    Route::put('/racks/{rack}', [RakbukuController::class, 'update'])->name('racks.update');
    Route::delete('/racks/{rack}', [RakbukuController::class, 'destroy'])->name('racks.destroy');

    // Kategori
    Route::prefix('categories')->name('categories.')->group(function() {
        Route::get('/', [KategoriController::class, 'index'])->name('index');
        Route::get('/create', [KategoriController::class, 'create'])->name('create');
        Route::post('/create', [KategoriController::class, 'store'])->name('store');
        Route::put('/{category}', [KategoriController::class, 'update'])->name('update');
        Route::delete('/{category}', [KategoriController::class, 'destroy'])->name('destroy');
        Route::get('/show', [KategoriController::class, 'index'])->name('show');
    });

    // Peminjaman
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman');
    Route::get('/peminjaman/search', [PeminjamanController::class, 'search'])->name('Peminjaman.search');
    Route::get('/search-books', [PeminjamanController::class, 'searchBookPage'])->name('search.book.page');
    Route::post('/store-peminjaman', [PeminjamanController::class, 'storePeminjaman'])->name('createPinjaman');
    Route::delete('/peminjaman/{id}', [PeminjamanController::class, 'destroy'])->name('peminjaman.destroy');
    Route::get('/search-member-by-email', [PeminjamanController::class, 'searchMemberByEmail'])->name('search.member.by.email');

    // Pengembalian
    Route::get('/pengembalian', [PengembalianController::class, 'index'])->name('pengembalian');
    Route::get('/pengembalian/search', [PengembalianController::class, 'search'])->name('pengembalian.search');
    Route::get('/pengembalian/cari', [PengembalianController::class, 'cari'])->name('pengembalian.cari');
    Route::put('/pengembalian/simpan/{peminjaman}', [PengembalianController::class, 'simpan'])->name('pengembalian.simpan');
    Route::delete('pengembalian/hapus/{id}', [PengembalianController::class, 'hapus'])->name('pengembalian.hapus');

});

// ====================================================
// 4. RUTE USER (OPSIONAL / JIKA LOGIN SEBAGAI USER)
// ====================================================
Route::middleware(['auth'])->group(function () {
    // Jika nanti user butuh halaman profile atau history peminjaman sendiri
    // Route::get('/profile', ...);
});