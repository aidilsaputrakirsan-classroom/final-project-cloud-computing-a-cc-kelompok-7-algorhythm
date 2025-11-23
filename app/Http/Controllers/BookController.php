<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Kategori;
use App\Models\Rack;
use App\Models\BookStock;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::with(['category', 'rack', 'bookStock'])->get();
        return view('Books.daftarbook', compact('books'));
    }

    public function create()
    {
        $categories = Kategori::all();
        $racks = Rack::all();
        return view('Books.create', compact('categories', 'racks'));
    }

    public function edit($id)
    {
        $book = Book::with('bookStock')->findOrFail($id);
        $categories = Kategori::all();
        $racks = Rack::all();

        return view('Books.edit', compact('book', 'categories', 'racks'));
    }

    public function store(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'title' => 'required|string|max:157',
            'author' => 'required|string|max:80',
            'publisher' => 'required|string|max:80',
            'isbn' => 'required|string|max:100|unique:tbl_books',
            'year' => 'required|integer',
            'rack_id' => 'required|exists:tbl_racks,id',
            'category_id' => 'required|exists:tbl_categories,id',
            'cover' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'required|string',
            'jmlh_tersedia' => 'required|integer',
        ]);

        // 2. Siapkan Data untuk Disimpan
        $data = $request->all();

        // 3. Handle Upload Gambar
        if ($request->hasFile('cover')) {
            $imageFile = $request->file('cover');
            $imageFileName = Str::random(10) . '.' . $imageFile->getClientOriginalExtension();
            $imageFile->move(public_path('cover_book'), $imageFileName);
            
            // Masukkan path gambar ke array data
            $data['book_cover'] = 'cover_book/' . $imageFileName;
        }

        // 4. Simpan Buku (Gunakan create agar ID langsung terbentuk)
        // Pastikan di Model Book.php property $guarded = []; atau $fillable sudah diisi
        $book = Book::create($data);

        // 5. Simpan Stok Buku
        // Karena $book sudah dicreate, $book->id pasti ada
        BookStock::create([
            'book_id' => $book->id,
            'jmlh_tersedia' => $request->input('jmlh_tersedia'),
        ]);

        return redirect()->route('books.index')->with('msg', 'Buku berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        // 1. Ambil Data Buku
        $book = Book::with('bookStock')->findOrFail($id);

        // 2. Validasi
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'publisher' => 'required|string|max:80',
            // Ignore ID saat validasi unique ISBN
            'isbn' => 'required|string|max:100|unique:tbl_books,isbn,' . $book->id,
            'year' => 'required|integer|min:1000|max:' . date('Y'),
            'category_id' => 'required|integer|exists:tbl_categories,id',
            'rack_id' => 'required|integer|exists:tbl_racks,id',
            'jumlah' => 'required|integer|min:0',
            'description' => 'required|string',
            'book_cover' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // 3. Siapkan Data Update
        $data = [
            'title' => $request->title,
            'author' => $request->author,
            'publisher' => $request->publisher,
            'isbn' => $request->isbn,
            'description' => $request->description,
            'year' => $request->year,
            'category_id' => $request->category_id,
            'rack_id' => $request->rack_id,
        ];

        // 4. Handle Update Gambar (Jika Ada)
        if ($request->hasFile('book_cover')) {
            // Hapus gambar lama
            if ($book->book_cover && File::exists(public_path($book->book_cover))) {
                File::delete(public_path($book->book_cover));
            }

            // Upload gambar baru
            $imageFile = $request->file('book_cover');
            $imageFileName = Str::random(10) . '.' . $imageFile->getClientOriginalExtension();
            $imageFile->move(public_path('cover_book'), $imageFileName);
            
            // Tambahkan ke array data
            $data['book_cover'] = 'cover_book/' . $imageFileName;
        }

        // 5. Update Tabel Buku
        $book->update($data);

        // 6. Update Tabel Stok
        // Menggunakan updateOrCreate untuk jaga-jaga jika data stok belum ada
        if ($book->bookStock) {
            $book->bookStock->update([
                'jmlh_tersedia' => $request->jumlah
            ]);
        } else {
            BookStock::create([
                'book_id' => $book->id,
                'jmlh_tersedia' => $request->jumlah
            ]);
        }

        // 7. Redirect ke Index
        return redirect()->route('books.index')->with('msg', 'Data buku berhasil diperbarui.');
    }

    public function showDetail($id)
    {
        $book = Book::findOrFail($id);
        // Kategori & Rack sebenarnya tidak perlu diambil lagi jika di view hanya menampilkan detail buku
        // Tapi dibiarkan jika Anda memakainya untuk dropdown modal (jika ada)
        $categories = Kategori::all();
        $racks = Rack::all();

        return view('Books.showDetail', compact('book', 'categories', 'racks'));
    }

    public function getBook($id)
    {
        $book = Book::with('bookStock')->findOrFail($id);
        return response()->json(['book' => $book]);
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);

        // Hapus File Gambar Cover
        if ($book->book_cover) {
            $coverBookPath = public_path($book->book_cover);
            if (File::exists($coverBookPath)) {
                File::delete($coverBookPath);
            }
        }

        // Hapus data buku (stok akan terhapus otomatis jika on delete cascade di database)
        // Jika tidak cascade, hapus manual: $book->bookStock()->delete();
        $book->delete();

        return redirect()->route('books.index')->with('msg', 'Book deleted successfully');
    }
}