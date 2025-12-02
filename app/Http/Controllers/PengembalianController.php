<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\BookStock;
use App\Models\Member;
use App\Models\Book;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\ActivityLog; // <--- PENTING: Import Model

class PengembalianController extends Controller
{
    public function index()
    {
        $pengembalians = Peminjaman::with(['member', 'book'])
                            ->whereNotNull('return_date')
                            ->orderBy('return_date', 'desc')
                            ->get();
        return view('Pengembalian.daftarpengembalian', ['pengembalians' => $pengembalians]);
    }

    public function search()
    {
        return view('Pengembalian.searchPengembalian');
    }

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
            $peminjaman = $query->clone()->whereHas('member', function ($q) use ($keyword) {
                $q->where('email', $keyword);
            })->whereNull('return_date')->get();
        } else {
            $peminjaman = collect([$peminjamanByResi]);
        }

        if ($peminjaman->isNotEmpty()) {
            return view('Pengembalian.searchPengembalian', ['peminjaman' => $peminjaman, 'keyword' => $keyword]);
        } else {
            $errors = ['Data peminjaman aktif tidak ditemukan.'];
            return redirect()->route('pengembalian.search')->withErrors($errors);
        }
    }

    public function simpan(Peminjaman $peminjaman)
    {
        if ($peminjaman->return_date) {
            return redirect()->route('pengembalian')->with('error', 'Buku ini sudah dikembalikan sebelumnya.');
        }

        try {
            $peminjaman->return_date = Carbon::now();
            $peminjaman->save();

        } catch (\Exception $e) {
            Log::error('Gagal simpan return_date: ' . $e->getMessage());
            return redirect()->route('pengembalian.search')->with('error', 'Gagal memproses pengembalian. Error DB: ' . $e->getMessage());
        }

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

        // --- REKAM LOG ---
        // Kita ambil data relasi untuk log
        $member = $peminjaman->member;
        $book = $peminjaman->book;

        ActivityLog::record(
            'RETURN',
            "Pengembalian Buku: {$book->title}",
            [
                'dikembalikan_oleh' => $member->first_name . ' ' . $member->last_name,
                'resi' => $peminjaman->resi_pjmn,
                'tanggal_kembali' => $peminjaman->return_date->format('d-m-Y H:i')
            ]
        );
        // -----------------

        return redirect()->route('pengembalian')->with('success', 'Buku telah berhasil dikembalikan.');
    }
}