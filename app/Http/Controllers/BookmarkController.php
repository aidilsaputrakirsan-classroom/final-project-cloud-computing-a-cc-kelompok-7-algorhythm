<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    // Fungsi Toggle (Tambah/Hapus)
    public function toggle($id)
    {
        $book = Book::findOrFail($id);
        // Toggle: Kalau ada dihapus, kalau tidak ada ditambah
        Auth::user()->bookmarks()->toggle($book->id);

        return back()->with('success', 'Status bookmark diperbarui!');
    }

    // Fungsi Halaman Daftar Bookmark
    public function index()
{
    // Mengambil buku yang di-bookmark user
    $books = Auth::user()->bookmarks()->with(['category', 'bookStock'])->paginate(8);
    
    // Mengirim data ke view 'Books.bookmarks'
    return view('Books.bookmarks', compact('books'));
}
}