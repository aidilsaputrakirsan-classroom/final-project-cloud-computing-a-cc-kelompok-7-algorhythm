<?php

namespace App\Http\Controllers;

use App\Models\Rack;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ActivityLog; // <--- PENTING: Import Model

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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'rak' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Simpan data baru
        $rack = Rack::create([
            'name' => $request->input('name'),
            'rak' => $request->input('rak'),
        ]);

        // --- REKAM LOG ---
        ActivityLog::record(
            'CREATE',
            'Menambahkan Rak Baru: ' . $rack->name,
            ['lokasi_rak' => $rack->rak]
        );
        // -----------------

        return redirect()->route('Rak.showdata')->with('msg', 'Rak berhasil ditambahkan');
    }

    public function edit($id)
    {
        $rack = Rack::findOrFail($id);
        return view('Rak.edit', compact('rack'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'rack_name' => 'required|string|max:255',
            'rak' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $rack = Rack::findOrFail($id);
        $oldName = $rack->name; // Simpan nama lama untuk log

        $rack->name = $request->input('rack_name'); 
        $rack->rak = $request->input('rak');
        $rack->save();

        // --- REKAM LOG ---
        ActivityLog::record(
            'UPDATE',
            'Memperbarui Rak: ' . $oldName,
            [
                'nama_baru' => $rack->name,
                'lokasi_baru' => $rack->rak
            ]
        );
        // -----------------

        return redirect()->route('Rak.showdata')->with('msg', 'Rak berhasil diperbarui');
    }

    public function destroy($id)
    {
        $rack = Rack::findOrFail($id);

        if ($rack->books()->count() > 0) {
            return redirect()->route('Rak.showdata')->with('error', 'Rak tidak dapat dihapus karena masih memiliki buku');
        }

        $namaRak = $rack->name; // Simpan nama sebelum dihapus
        $rack->delete();

        // --- REKAM LOG ---
        ActivityLog::record(
            'DELETE',
            'Menghapus Rak: ' . $namaRak
        );
        // -----------------

        return redirect()->route('Rak.showdata')->with('msg', 'Rak berhasil dihapus');
    }
}