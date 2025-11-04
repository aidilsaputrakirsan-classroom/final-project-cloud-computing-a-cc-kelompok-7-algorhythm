<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman; // Kita HANYA pakai model Peminjaman
use App\Models\BookStock; // Pastikan Anda punya model BookStock
use App\Models\Member;    // Pastikan Anda punya model Member
use App\Models\Book;      // Pastikan Anda punya model Book
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log; // Untuk mencatat error

class PengembalianController extends Controller
{
    /**
     * Menampilkan halaman histori pengembalian (yang sudah dikembalikan).
     */
    public function index()
    {
        $pengembalians = Peminjaman::with(['member', 'book'])
                            ->whereNotNull('return_date')
                            ->orderBy('return_date', 'desc')
                            ->get();
        return view('Pengembalian.daftarpengembalian', ['pengembalians' => $pengembalians]);
    }

    /**
     * Menampilkan halaman pencarian peminjaman (untuk dikembalikan).
     */
    public function search()
    {
        return view('Pengembalian.searchPengembalian');
    }

    /**
     * Memproses pencarian data peminjaman yang MASIH AKTIF.
     */
    public function cari(Request $request)
    {
        $request->validate([
            'keyword' => 'required|string',
        ]);

        $keyword = $request->input('keyword');
        $query = Peminjaman::with(['member', 'book']);

        $peminjamanByResi = $query->where('resi_pjmn', $keyword)
            ->whereNull('return_date')
            ->first();

        if (!$peminjamanByResi) {
            $peminjaman = $query->whereHas('member', function ($q) use ($keyword) {
                $q->where('email', $keyword);
            })->whereNull('return_date')->get();
        } else {
            $peminjaman = collect([$peminjamanByResi]);
        }

        if ($peminjaman->isNotEmpty()) {
            return view('Pengembalian.searchPengembalian', ['peminjaman' => $peminjaman]);
        } else {
            $errors = ['Data peminjaman aktif tidak ditemukan.'];
            return redirect()->route('pengembalian.search')->withErrors($errors);
        }
    }


    /**
     * VERSI BARU - Diperbaiki untuk mengambil ID dari Request
     * Menyimpan data pengembalian.
     */
    public function simpan(Request $request)
    {
        // 1. Validasi bahwa 'id' ada di dalam form
        $request->validate([
            'id' => 'required|integer|exists:tbl_peminjaman,id',
        ]);

        $peminjaman = Peminjaman::find($request->input('id'));

        // 2. Cek dulu, jangan-jangan sudah dikembalikan
        if ($peminjaman->return_date) {
            return redirect()->route('pengembalian')->with('error', 'Buku ini sudah dikembalikan sebelumnya.');
        }

        // 3. LANGSUNG SIMPAN HAL UTAMA (return_date)
        $peminjaman->return_date = Carbon::now();
        $peminjaman->save();

        // 4. SECARA TERPISAH, coba kembalikan stok
        try {
            $bookStock = BookStock::where('book_id', $peminjaman->book_id)->first();
            
            if ($bookStock) {
                $bookStock->increment('jmlh_tersedia');
            } else {
                // Jika tidak ada data stok, buat log
                Log::warning('Tidak ditemukan data stok untuk book_id: ' . $peminjaman->book_id);
            }

        } catch (\Exception $stockError) {
            // Jika GAGAL update stok, jangan batalkan pengembalian.
            // Cukup catat errornya dan tetap lanjutkan.
            Log::error('GSERR: Gagal update stok: ' . $stockError->getMessage());
            
            // Redirect dengan pesan "sukses" tapi ada peringatan
            return redirect()->route('pengembalian')->with('success', 'Buku berhasil dikembalikan (Peringatan: ' . $stockError->getMessage() . ').');
        }

        // 5. Jika semua berhasil
        return redirect()->route('pengembalian')->with('success', 'Buku telah berhasil dikembalikan.');
    }
}

