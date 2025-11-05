<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import Fasad Auth
use Illuminate\Http\RedirectResponse; // Import RedirectResponse

class LoginController extends Controller
{
    /**
     * Menampilkan halaman/view login.
     */
    public function showLoginForm()
    {
        // Pastikan Anda punya file 'login.blade.php' di folder resources/views
        return view('login');
    }

    /**
     * Menangani percobaan autentikasi.
     */
    public function authenticate(Request $request): RedirectResponse
    {
        // 1. Validasi input (email dan password Wajib diisi)
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Coba lakukan login
        // Kita juga tambahkan 'remember'
        $remember = $request->has('remember'); // Cek apakah checkbox 'remember' dicentang

        if (Auth::attempt($credentials, $remember)) {
            // 3. Jika berhasil
            $request->session()->regenerate(); // Regenerate session untuk keamanan

            // Arahkan user ke halaman yang tadinya ingin mereka tuju,
            // atau default ke '/dashboard'
            return redirect()->intended('dashboard');
        }

        // 4. Jika gagal
        // Kembalikan ke halaman login dengan pesan error
        return back()->with('error', 'Login gagal! Email atau password salah.');
    }

    /**
     * Menangani proses logout.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout(); // Logout user

        $request->session()->invalidate(); // Matikan session lama

        $request->session()->regenerateToken(); // Buat token CSRF baru

        return redirect('/'); // Arahkan kembali ke halaman utama (yang akan ke login)
    }
}
