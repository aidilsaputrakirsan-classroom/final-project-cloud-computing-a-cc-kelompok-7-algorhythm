<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog; // Integrasi dengan Activity Log Anda
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    // Menampilkan Form Register
    public function showRegistrationForm()
    {
        return view('register');
    }

    // Proses Simpan Data User
    public function register(Request $request)
    {
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'], // confirmed = harus ada input password_confirmation
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // 2. Buat User Baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // Set default role sebagai User Biasa
        ]);

        // 3. Rekam Log Aktivitas (Karena Anda sudah punya fiturnya)
        // Kita pakai try-catch agar jika log error, user tetap terdaftar
        try {
            if (class_exists(ActivityLog::class)) {
                ActivityLog::create([
                    'user_id' => $user->id,
                    'action' => 'REGISTER',
                    'description' => 'Pengguna baru mendaftar: ' . $user->name,
                    'details' => json_encode(['email' => $user->email])
                ]);
            }
        } catch (\Exception $e) {
            // Abaikan error log
        }

        // 4. Otomatis Login setelah register
        Auth::login($user);

        // 5. Redirect ke Landing Page
        return redirect()->route('landing')->with('success', 'Selamat datang! Akun Anda berhasil dibuat.');
    }
}