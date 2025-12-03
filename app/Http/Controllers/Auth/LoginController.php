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
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    $remember = $request->has('remember');

    if (Auth::attempt($credentials, $remember)) {
        $request->session()->regenerate();

        // --- LOGIKA REDIRECT BERDASARKAN ROLE ---
        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->intended('dashboard'); // Admin ke Dashboard
        }

        return redirect()->route('landing'); // User biasa ke Landing Page
        // ----------------------------------------
    }

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
