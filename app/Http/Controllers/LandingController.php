<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book; // Pastikan Model Book di-import

class LandingController extends Controller
{
    public function index(Request $request)
{
    // Logika pencarian
    $query = Book::query();
    if ($request->has('search')) {
        $query->where('title_book', 'like', '%' . $request->search . '%')
              ->orWhere('author', 'like', '%' . $request->search . '%');
    }
    
    // Ambil data dengan relasi kategori (agar tidak N+1 problem)
    $books = $query->with('kategori')->latest()->paginate(8);
    
    return view('landing_page', compact('books'));
}
}


