<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BukuController extends Controller
{
    /**
     * Menampilkan halaman data buku.
     */
    public function index()
    {
        // Untuk saat ini, kita hanya tampilkan view-nya saja
        // Nanti di sini Anda akan mengambil data dari database

        return view('buku.index'); 
    }
}