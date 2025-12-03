<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Models\Member; // Pastikan Model Member diimport
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index(Request $request)
    {
        // 1. Logika pencarian buku (Tetap sama)
        $query = Book::query();
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%') // Perbaikan: sesuaikan kolom 'title' bukan 'title_book' jika migration pakai 'title'
                  ->orWhere('author', 'like', '%' . $request->search . '%');
        }

        // Ambil data buku
        $books = $query->latest()->paginate(8);

        // 2. Logika Hitung User Biasa (Non-Member Dashboard)
        // Ambil User role 'user' yang ID-nya TIDAK ADA di tabel members
        $totalUsers = User::where('role', 'user')
                        ->whereNotIn('id', Member::select('user_id'))
                        ->count();

        // Kirim variabel $totalUsers ke view
        return view('landing_page', compact('books', 'totalUsers'));
    }
}