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
        
        // Buat query dasar
        $query = Peminjaman::with(['member', 'book']);

        // Cari berdasarkan resi dulu
        $peminjamanByResi = $query->where('resi_pjmn', $keyword)
            ->whereNull('return_date') // Hanya yang masih aktif
            ->first();

        // Jika tidak ketemu, baru cari berdasarkan email
        if (!$peminjamanByResi) {
            // Clone query agar tidak tercampur
            $peminjaman = $query->clone()->whereHas('member', function ($q) use ($keyword) {
                $q->where('email', $keyword);
            })->whereNull('return_date')->get(); // Hanya yang masih aktif
        } else {
            // Jika ketemu by resi, jadikan collection agar @foreach bisa jalan
            $peminjaman = collect([$peminjamanByResi]);
        }

        if ($peminjaman->isNotEmpty()) {
            return view('Pengembalian.searchPengembalian', ['peminjaman' => $peminjaman, 'keyword' => $keyword]);
        } else {
            $errors = ['Data peminjaman aktif tidak ditemukan.'];
            return redirect()->route('pengembalian.search')->withErrors($errors);
        }
    }


    /**
     * INI ADALAH FUNGSI YANG DIPERBAIKI
     * 1. Kita hanya menerima (Peminjaman $peminjaman) dari URL.
     * 2. Kita HAPUS total Validator '$request->validate()' yang menyebabkan error "id field is required".
     */
    public function simpan(Peminjaman $peminjaman)
    {
        // 1. Cek dulu, jangan-jangan sudah dikembalikan
        if ($peminjaman->return_date) {
            return redirect()->route('pengembalian')->with('error', 'Buku ini sudah dikembalikan sebelumnya.');
        }

        // 2. Langsung simpan return_date
        try {
            $peminjaman->return_date = Carbon::now();
            $peminjaman->save();

        } catch (\Exception $e) {
            // Jika GAGAL, ini adalah error DB yang Anda lihat sebelumnya
            Log::error('Gagal simpan return_date: ' . $e->getMessage());
            // Kita kembalikan ke halaman search, BUKAN back()
            return redirect()->route('pengembalian.search')->with('error', 'Gagal memproses pengembalian. Error DB: ' . $e->getMessage());
        }

        // 3. SECARA TERPISAH, coba kembalikan stok
        try {
            $bookStock = BookStock::where('book_id', $peminjaman->book_id)->first();
            
            if ($bookStock) {
                $bookStock->increment('jmlh_tersedia');
            } else {
                Log::warning('Tidak ditemukan data stok untuk book_id: ' . $peminjaman->book_id);
            }

        } catch (\Exception $stockError) {
            Log::error('Gagal update stok: ' . $stockError->getMessage());
            return redirect()->route('pengembalian')->with('success', 'Buku berhasil dikembalikan (Peringatan: Gagal update stok).');
        }

        // 4. Jika semua berhasil
        return redirect()->route('pengembalian')->with('success', 'Buku telah berhasil dikembalikan.');
    }
}