<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Member;
use App\Models\Peminjaman;
use App\Models\Book;
use App\Models\BookStock; // Pastikan Anda punya model ini
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    /**
     * Menampilkan daftarpeminjaman.blade.php
     * (route('peminjaman'))
     */
    public function index(Request $request)
    {
        // Ambil hanya peminjaman yang belum dikembalikan (return_date == null)
        // Sesuai dengan logika @if (is_null($peminjaman->return_date)) di view Anda
        $peminjamans = Peminjaman::with(['member', 'book']) // Eager load relasi
                        ->whereNull('return_date')
                        ->orderBy('created_at', 'desc')
                        ->get();

        // Asumsi view Anda ada di /resources/views/Peminjaman/daftarpeminjaman.blade.php
        return view('Peminjaman.daftarpeminjaman', compact('peminjamans'));
    }

    /**
     * Menampilkan search.blade.php
     * (route('Peminjaman.search'))
     */
    public function search()
    {
        // Asumsi view Anda ada di /resources/views/Peminjaman/search.blade.php
        return view('Peminjaman.search');
    }

    /**
     * Menangani AJAX search member by email dari search.blade.php
     * (route('search.member.by.email'))
     */
    public function searchMemberByEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $member = Member::where('email', $request->input('email'))->first();

        if ($member) {
            return response()->json(['member' => $member]);
        }
        
        return response()->json(['member' => null]);
    }

    /**
     * Menangani AJAX scan QR code dari search.blade.php
     * (route('scan.member.by.qrcode'))
     * * CATATAN: Logika ini disesuaikan dengan file search.blade.php Anda, 
     * BUKAN dengan referensi controller Anda (yang menggunakan Crypt).
     * File JS Anda mengirimkan QR code mentah, bukan data terenkripsi.
     */
    public function scanMemberByQRCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'qr_code' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $qrCodeContent = $request->input('qr_code');
        $member = Member::where('qr_code', $qrCodeContent)->first();

        if ($member) {
            // Logika cek QR expired dari file search.blade.php Anda
            $updatedAt = new \Carbon\Carbon($member->updated_at);
            if ($updatedAt->diffInMinutes(now()) > 1) {
                 return response()->json(['error' => 'QR Code Expired'], 410); // 410 Gone
            }
            return response()->json(['member' => $member]);
        }
        
        return response()->json(['error' => 'Member not found'], 404);
    }


    /**
     * Menampilkan searchBook.blade.php
     * (route('search.book.page'))
     */
    public function searchBookPage(Request $request)
    {
        $searchTerm = $request->input('search');
        $memberId = $request->input('member_id');
        $member = Member::find($memberId);

        if (!$member) {
            return redirect()->route('Peminjaman.search')->with('error', 'Member tidak ditemukan.');
        }

        $books = collect(); // Koleksi kosong by default

        if ($searchTerm) {
            $books = Book::with(['category', 'rack', 'bookStock']) // Eager load relasi
                ->where(function($query) use ($searchTerm) {
                    $query->where('title', 'LIKE', '%' . $searchTerm . '%')
                        ->orWhere('author', 'LIKE', '%' . $searchTerm . '%')
                        ->orWhere('publisher', 'LIKE', '%' . $searchTerm . '%')
                        ->orWhere('isbn', 'LIKE', '%' . $searchTerm . '%')
                        ->orWhereHas('category', function($q) use ($searchTerm) {
                            $q->where('name', 'LIKE', '%' . $searchTerm . '%');
                        });
                })
                ->get();
        }

        // Asumsi view Anda ada di /resources/views/Peminjaman/searchBook.blade.php
        return view('Peminjaman.searchBook', compact('books', 'member', 'memberId'));
    }

    /**
     * Menyimpan data peminjaman baru
     * (route('createPinjaman'))
     */
    public function storePeminjaman(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'member_id' => 'required|exists:tbl_members,id',
            'book_id' => 'required|exists:tbl_books,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $memberId = $request->input('member_id');
        $bookId = $request->input('book_id');

        $book = Book::with('bookStock')->find($bookId); // Ambil buku DENGAN stoknya
        if (!$book) {
            return redirect()->back()->with('error', 'Buku tidak ditemukan.');
        }

        // --- Logika Pengecekan dari Referensi Anda ---

        // 1. Periksa apakah anggota sudah meminjam buku yang sama
        $existingLoan = Peminjaman::where('member_id', $memberId)
            ->where('book_id', $bookId)
            ->whereNull('return_date') // Yang masih dipinjam
            ->first();

        if ($existingLoan) {
            return redirect()->back()->with('error', 'Anggota sudah meminjam buku yang sama.');
        }

        // 2. Periksa batas maksimal peminjaman (dari referensi Anda)
        $borrowedBooksCount = Peminjaman::where('member_id', $memberId)
            ->whereNull('return_date')
            ->count();
        
        if ($borrowedBooksCount >= 3) {
            return redirect()->back()->with('error', 'Anggota sudah mencapai batas maksimal 3 buku peminjaman.');
        }

        // 3. Periksa stok buku (dari referensi Anda)
        if ($book->bookStock->jmlh_tersedia <= 0) {
            return redirect()->back()->with('error', 'Buku tidak tersedia untuk dipinjam.');
        }

        // 4. Pengecekan Denda di-skip karena model Denda tidak ada.

        // --- Akhir Logika Pengecekan ---

        // Gunakan Transaksi Database agar aman
        try {
            DB::beginTransaction();

            // Buat resi unik (dari referensi Anda)
            $uniqueCode = 'PJMN-' . strtoupper(Str::random(5));
            while (Peminjaman::where('resi_pjmn', $uniqueCode)->exists()) {
                $uniqueCode = 'PJMN-' . strtoupper(Str::random(5));
            }

            // 1. Buat catatan peminjaman baru
            Peminjaman::create([
                'resi_pjmn' => $uniqueCode,
                'member_id' => $memberId,
                'book_id' => $bookId,
                'return_date' => null,
            ]);

            // 2. Kurangi jumlah buku yang tersedia (dari referensi Anda)
            $bookStock = $book->bookStock;
            $bookStock->jmlh_tersedia -= 1; // atau $bookStock->decrement('jmlh_tersedia');
            $bookStock->save();

            DB::commit(); // Simpan semua perubahan

            // Redirect ke halaman daftar peminjaman dengan pesan sukses
            return redirect()->route('peminjaman')->with('success', 'Buku berhasil dipinjam.');

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan jika ada error
            // Log::error($e->getMessage()); // Opsional
            return redirect()->back()->with('error', 'Terjadi kesalahan. Gagal meminjam buku.');
        }
    }

    /**
     * Menghapus peminjaman (opsional, dari referensi Anda)
     */
    public function destroy($id)
    {
        $peminjaman = Peminjaman::find($id);
        if (!$peminjaman) {
            return redirect()->back()->with('error', 'Data peminjaman tidak ditemukan.');
        }

        // Gunakan transaksi untuk keamanan
        try {
            DB::beginTransaction();

            // Kembalikan jumlah buku yang tersedia
            $bookStock = $peminjaman->book->bookStock;
            $bookStock->jmlh_tersedia += 1; // atau $bookStock->increment('jmlh_tersedia');
            $bookStock->save();

            // Hapus data peminjaman (soft delete jika model menggunakan SoftDeletes)
            $peminjaman->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Data peminjaman berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus data peminjaman.');
        }
    }
}