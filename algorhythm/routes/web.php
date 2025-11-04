<?php

use Illuminate\Support\Facades\Route;
// 1. Arahkan ke Controller yang baru Anda buat
use App\Http\Controllers\MemberController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\RakbukuController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BookController; 
use App\Http\Controllers\PeminjamanController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Rute Halaman Utama
Route::get('/', function () {
    // Jika user sudah login, arahkan ke dashboard. Jika belum, ke login.
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});


// === GRUP AUTENTIKASI MANUAL ===

// 2. Rute untuk MENAMPILKAN form login
// Kita beri nama 'login' agar middleware auth Laravel tahu ke mana harus me-redirect
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');

// 3. Rute untuk MEMPROSES data login saat tombol "Masuk" diklik
Route::post('login', [LoginController::class, 'authenticate'])->name('login.authenticate');

// 4. Rute untuk logout
Route::post('logout', [LoginController::class, 'logout'])->name('logout');


// === RUTE YANG DILINDUNGI ===

// 5. Halaman yang hanya bisa diakses setelah login
// Middleware 'auth' akan otomatis melempar user ke rute 'login' jika mereka belum login
// Kita arahkan dashboard langsung ke daftar rak agar hanya fitur login + rak yang tersisa
Route::get('dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

// Route untuk Member
Route::get('/member', [MemberController::class, 'index'])->name('member.index');
Route::get('/member/create', [MemberController::class, 'create'])->name('member.create'); // INI YANG HILANG
Route::post('/member', [MemberController::class, 'store'])->name('member.store');
Route::get('/member/{id}/edit', [MemberController::class, 'edit'])->name('member.edit');
Route::put('/member/{id}', [MemberController::class, 'update'])->name('member.update');
Route::delete('/member/{id}', [MemberController::class, 'destroy'])->name('member.destroy');

// Daftar buku
    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
    Route::get('/book/{id}/', [BookController::class, 'showDetail'])->name('Books.showDetail');
    Route::get('/books/{id}update', [BookController::class, 'getBook'])->name('books.getBook');
    Route::post('/books', [BookController::class, 'store'])->name('books.store');
    Route::delete('/books/{id}', [BookController::class, 'destroy'])->name('books.destroy');
    Route::put('/books/{book}', [BookController::class, 'update'])->name('books.books.update');


// Rak buku
Route::get('/rak', [RakbukuController::class, 'index'])->name('Rak.showdata');
Route::get('/rak/create', [RakbukuController::class, 'create'])->name('Rak.createRak');
Route::post('/rak/create', [RakbukuController::class, 'store'])->name('Rak.storeRak');
Route::delete('/racks/{rack}', [RakbukuController::class, 'destroy'])->name('racks.destroy');
Route::put('/racks/{rack}', [RakbukuController::class, 'update'])->name('racks.update');

// Kategori Buku
    Route::get('/categories', [KategoriController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [KategoriController::class, 'create'])->name('categories.create');
    Route::post('/categories/create', [KategoriController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [KategoriController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [KategoriController::class, 'destroy'])->name('categories.destroy');
    Route::get('/categories/show', [KategoriController::class, 'index'])->name('categories.show');

//Peminjaman
Route::middleware(['auth'])->group(function () {
    
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman');

    Route::get('/peminjaman/search', [PeminjamanController::class, 'search'])->name('Peminjaman.search');

    Route::get('/search-books', [PeminjamanController::class, 'searchBookPage'])->name('search.book.page');

    Route::post('/store-peminjaman', [PeminjamanController::class, 'storePeminjaman'])->name('createPinjaman');

    Route::delete('/peminjaman/{id}', [PeminjamanController::class, 'destroy'])->name('peminjaman.destroy');

    Route::get('/search-member-by-email', [PeminjamanController::class, 'searchMemberByEmail'])->name('search.member.by.email');
    
});
