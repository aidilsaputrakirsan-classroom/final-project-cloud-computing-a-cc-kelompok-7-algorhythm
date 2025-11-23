<?php

namespace App\Http\Controllers;

use App\Models\Rack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RakbukuController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $racks = Rack::withCount('books')
            ->where('name', 'like', "%$search%")
            ->orWhere('rak', 'like', "%$search%")
            ->get();

        return view('Rak.showdata', compact('racks'));
    }

    public function create()
    {
        return view('Rak.createRak');
    }

    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'rak' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Simpan data baru
        Rack::create([
            'name' => $request->input('name'),
            'rak' => $request->input('rak'),
        ]);

        return redirect()->route('Rak.showdata')->with('msg', 'Rak berhasil ditambahkan');
    }

    // --- [METHOD TAMBAHAN: EDIT] ---
    // Method ini berfungsi untuk menampilkan halaman form edit
    public function edit($id)
    {
        $rack = Rack::findOrFail($id);
        // Pastikan folder view sesuai: resources/views/Rak/edit.blade.php
        return view('Rak.edit', compact('rack'));
    }
    // -------------------------------

    public function update(Request $request, $id)
    {
        // Validasi input (Sesuaikan nama field dengan form edit.blade.php)
        // Di form edit kita menggunakan name="rack_name" dan name="rak"
        $validator = Validator::make($request->all(), [
            'rack_name' => 'required|string|max:255',
            'rak' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $rack = Rack::findOrFail($id);
        
        // Update data ke database
        // Kolom 'name' di DB diisi dengan input 'rack_name' dari form
        $rack->name = $request->input('rack_name'); 
        $rack->rak = $request->input('rak');
        $rack->save();

        // Redirect kembali ke halaman index (Rak.showdata)
        return redirect()->route('Rak.showdata')->with('msg', 'Rak berhasil diperbarui');
    }

    public function destroy($id)
    {
        $rack = Rack::findOrFail($id);

        // Cek apakah rak masih memiliki buku
        if ($rack->books()->count() > 0) {
            return redirect()->route('Rak.showdata')->with('error', 'Rak tidak dapat dihapus karena masih memiliki buku');
        }

        $rack->delete();

        return redirect()->route('Rak.showdata')->with('msg', 'Rak berhasil dihapus');
    }
}