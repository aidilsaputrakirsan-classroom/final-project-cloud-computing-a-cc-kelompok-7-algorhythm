<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index(Request $request)
    {
        // Logika pencarian buku
        $query = Book::query();
        if ($request->has('search')) {
            $query->where('title_book', 'like', '%' . $request->search . '%')
                  ->orWhere('author', 'like', '%' . $request->search . '%');
        }

        // Ambil data buku (paginate 8 per halaman)
        $books = $query->latest()->paginate(8);

        return view('landing_page', compact('books'));
    }
}