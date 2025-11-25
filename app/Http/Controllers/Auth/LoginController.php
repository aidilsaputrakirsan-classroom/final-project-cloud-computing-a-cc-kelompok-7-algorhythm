<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class LoginController extends Controller
{
    /**
     * Menampilkan halaman/view login.
     */
    public function showLoginForm()
    {
        return view('login');
    }

    /**
     * Menangani percobaan autentikasi.
     */
    public function authenticate(Request $request): RedirectResponse
    {
        // 1. Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Cek "Remember Me"
        $remember = $request->has('remember');

        // 3. Proses Login
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // === BAGIAN YANG DIUPDATE (LOGIKA REDIRECT) ===
            // Ambil data user yang sedang login
            $user = Auth::user();

            // Cek Role
            if ($user->role === 'admin') {
                return redirect()->route('dashboard'); // Admin ke Dashboard
            }

            // Jika bukan admin, ke Landing Page
            return redirect()->route('landing');
            // ==============================================
        }

        // 4. Jika gagal
        return back()->with('error', 'Login gagal! Email atau password salah.');
    }

    /**
     * Menangani proses logout.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/'); 
    }
}