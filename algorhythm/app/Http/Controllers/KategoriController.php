<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $categories = Kategori::where('name', 'like', '%' . $search . '%')->get();

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
        $category->delete();

        return redirect()->route('categories.index')->with('msg', 'Kategori berhasil dihapus');
    }
}