<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\RakbukuController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BookController; 
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ====================================================
// 1. RUTE PUBLIK (Bisa diakses siapa saja)
// ====================================================

// Halaman Utama
Route::get('/', [LandingController::class, 'index'])->name('landing');

// DETAIL BUKU PUBLIK (PINDAHKAN KE SINI)
// Ini agar guest/pengunjung bisa melihat detail tanpa login
Route::get('/buku/{id}', [BookController::class, 'showPublicDetail'])->name('books.public.detail');

// Authentication Routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'authenticate'])->name('login.authenticate');
Route::post('logout', [LoginController::class, 'logout'])->name('logout');


// ====================================================
// 2. RUTE USER & ADMIN (Harus Login)
// ====================================================
Route::middleware(['auth'])->group(function () {
    
    // Rute ini opsional, bisa dihapus jika sudah pakai yang public di atas.
    // Tapi jika ingin Admin punya tampilan detail beda, biarkan saja.
    Route::get('/book/{id}/', [BookController::class, 'showDetail'])->name('Books.showDetail');
    // Route Toggle Bookmark (Tombol Love)
    Route::post('/bookmark/{id}', [App\Http\Controllers\BookmarkController::class, 'toggle'])->name('bookmark.toggle');

    Route::get('/koleksi-saya', [App\Http\Controllers\BookmarkController::class, 'index'])->name('bookmarks.index');

});


// ====================================================
// 3. RUTE KHUSUS ADMIN (Middleware 'is_admin')
// ====================================================
Route::middleware(['auth', 'is_admin'])->group(function () {

    // Dashboard Admin
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Route untuk Member
    Route::get('/member', [MemberController::class, 'index'])->name('member.index');
    Route::get('/member/create', [MemberController::class, 'create'])->name('member.create');
    Route::post('/member', [MemberController::class, 'store'])->name('member.store');
    Route::get('/member/{id}/edit', [MemberController::class, 'edit'])->name('member.edit');
    Route::put('/member/{id}', [MemberController::class, 'update'])->name('member.update');
    Route::delete('/member/{id}', [MemberController::class, 'destroy'])->name('member.destroy');

    // Daftar buku (HAPUS RUTE '/buku/{id}' DARI SINI)
    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
    Route::post('/books', [BookController::class, 'store'])->name('books.store');
    Route::get('/books/{id}/edit', [BookController::class, 'edit'])->name('books.edit');
    Route::put('/books/{id}', [BookController::class, 'update'])->name('books.update');
    Route::delete('/books/{id}', [BookController::class, 'destroy'])->name('books.destroy');

    // ... (Sisa routing Rak, Kategori, Peminjaman, dll biarkan sama) ...
    
    // Rak Buku
    Route::get('/rak', [RakbukuController::class, 'index'])->name('Rak.showdata');
    Route::get('/rak/create', [RakbukuController::class, 'create'])->name('Rak.createRak');
    Route::post('/rak/create', [RakbukuController::class, 'store'])->name('Rak.storeRak');
    Route::get('/racks/{rack}/edit', [RakbukuController::class, 'edit'])->name('racks.edit');
    Route::put('/racks/{rack}', [RakbukuController::class, 'update'])->name('racks.update');
    Route::delete('/racks/{rack}', [RakbukuController::class, 'destroy'])->name('racks.destroy');

    // Kategori Buku
    Route::get('/categories', [KategoriController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [KategoriController::class, 'create'])->name('categories.create');
    Route::post('/categories', [KategoriController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [KategoriController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [KategoriController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [KategoriController::class, 'destroy'])->name('categories.destroy');
    Route::get('/categories/{category}', [KategoriController::class, 'show'])->name('categories.show');

    // Peminjaman
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman');
    Route::get('/peminjaman/search', [PeminjamanController::class, 'search'])->name('Peminjaman.search');
    Route::get('/search-books', [PeminjamanController::class, 'searchBookPage'])->name('search.book.page');
    Route::post('/store-peminjaman', [PeminjamanController::class, 'storePeminjaman'])->name('createPinjaman');
    Route::delete('/peminjaman/{id}', [PeminjamanController::class, 'destroy'])->name('peminjaman.destroy');
    Route::get('/search-member-by-email', [PeminjamanController::class, 'searchMemberByEmail'])->name('search.member.by.email');
    
    // Pengembalian buku
    Route::get('/pengembalian', [PengembalianController::class, 'index'])->name('pengembalian');
    Route::get('/pengembalian/search', [PengembalianController::class, 'search'])->name('pengembalian.search');
    Route::get('/pengembalian/cari', [PengembalianController::class, 'cari'])->name('pengembalian.cari');
    Route::put('/pengembalian/simpan/{peminjaman}', [PengembalianController::class, 'simpan'])->name('pengembalian.simpan');
    Route::delete('pengembalian/hapus/{id}', [PengembalianController::class, 'hapus'])->name('pengembalian.hapus');

});
