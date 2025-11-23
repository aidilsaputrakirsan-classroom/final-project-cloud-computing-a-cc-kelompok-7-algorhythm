<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB; // Tambahkan ini agar DB::raw berfungsi

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', ''); 
        $categories = Kategori::withCount([
            'books',
            'bookStocks as total_books' => function ($query) {
                $query->select(DB::raw('sum(jmlh_tersedia)'));
            }
        ])
            ->where('name', 'like', '%' . $search . '%')
            ->get();

        // Pastikan nama view ini sesuai dengan file Anda
        return view('kategori.showkategori', compact('categories'));
    }

    public function create()
    {
        return view('kategori.createkategori');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if (Kategori::where('name', $request->input('name'))->exists()) {
            return redirect()->back()->with('msg', 'Nama kategori sudah ada')->withInput();
        }

        Kategori::create([
            'name' => $request->input('name'),
        ]);

        return redirect()->route('categories.index')->with('msg', 'Kategori berhasil ditambahkan');
    }

    // --- [INI YANG DITAMBAHKAN] ---
public function edit($id)
    {
        $category = Kategori::findOrFail($id);
        
        // PERBAIKAN: Mengganti 'categories.edit' menjadi 'kategori.edit'
        // Pastikan file edit.blade.php sudah Anda buat di dalam folder resources/views/kategori/
        return view('kategori.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $category = Kategori::findOrFail($id);
        $category->name = $request->input('name');
        $category->save();

        return redirect()->route('categories.index')->with('msg', 'Kategori berhasil diperbarui');
    }

    public function destroy($id)
    {
        $category = Kategori::findOrFail($id);
        if ($category->books()->count() > 0) {
            return redirect()->route('categories.index')->with('error', 'Kategori tidak dapat dihapus karena masih memiliki buku');
        }
        $category->delete();

        return redirect()->route('categories.index')->with('msg', 'Kategori berhasil dihapus');
    }
}