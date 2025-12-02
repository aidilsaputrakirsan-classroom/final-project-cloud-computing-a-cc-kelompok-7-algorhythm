<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\ActivityLog; // <--- PENTING: Import Model

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

        $kategori = Kategori::create([
            'name' => $request->input('name'),
        ]);

        // --- REKAM LOG ---
        ActivityLog::record(
            'CREATE',
            'Menambahkan Kategori Baru: ' . $kategori->name
        );
        // -----------------

        return redirect()->route('categories.index')->with('msg', 'Kategori berhasil ditambahkan');
    }

    public function edit($id)
    {
        $category = Kategori::findOrFail($id);
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
        $oldName = $category->name;
        
        $category->name = $request->input('name');
        $category->save();

        // --- REKAM LOG ---
        ActivityLog::record(
            'UPDATE',
            'Memperbarui Kategori: ' . $oldName,
            ['nama_baru' => $category->name]
        );
        // -----------------

        return redirect()->route('categories.index')->with('msg', 'Kategori berhasil diperbarui');
    }

    public function destroy($id)
    {
        $category = Kategori::findOrFail($id);
        if ($category->books()->count() > 0) {
            return redirect()->route('categories.index')->with('error', 'Kategori tidak dapat dihapus karena masih memiliki buku');
        }
        
        $namaKategori = $category->name;
        $category->delete();

        // --- REKAM LOG ---
        ActivityLog::record(
            'DELETE',
            'Menghapus Kategori: ' . $namaKategori
        );
        // -----------------

        return redirect()->route('categories.index')->with('msg', 'Kategori berhasil dihapus');
    }
}