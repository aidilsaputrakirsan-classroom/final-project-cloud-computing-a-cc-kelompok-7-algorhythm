<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class LandingController extends Controller
{
    public function index(Request $request)
    {
        // Mulai query dari Model Book
        $query = Book::query();

        // Logika Pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%') // Ganti 'title_book' jadi 'title' sesuai database Anda
                  ->orWhere('author', 'like', '%' . $search . '%');
            });
        }
        
        // Ambil data dengan relasi 'category' (sesuai nama fungsi di Model Book)
        // Gunakan 'latest()' untuk urutan terbaru
        // Gunakan 'paginate(8)' untuk membatasi 8 buku per halaman
        $books = $query->with('category')->latest()->paginate(8);
        
        // Kirim data ke view
        return view('landing_page', compact('books'));
    }
}