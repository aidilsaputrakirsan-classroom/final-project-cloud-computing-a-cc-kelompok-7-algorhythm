<?php

use Illuminate\Support\Facades\Route;
// 1. Arahkan ke Controller yang baru Anda buat
use App\Http\Controllers\Auth\LoginController;

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
Route::get('dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');
