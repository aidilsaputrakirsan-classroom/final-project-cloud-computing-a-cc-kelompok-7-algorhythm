<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Member;
use App\Models\Peminjaman;
use App\Models\Book;
use App\Models\BookStock;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\ActivityLog; // <--- PENTING: Import Model

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $peminjamans = Peminjaman::with(['member', 'book'])
                        ->whereNull('return_date')
                        ->orderBy('created_at', 'desc')
                        ->get();

        return view('Peminjaman.daftarpeminjaman', compact('peminjamans'));
    }

    public function search()
    {
        return view('Peminjaman.search');
    }

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
            $updatedAt = new \Carbon\Carbon($member->updated_at);
            if ($updatedAt->diffInMinutes(now()) > 1) {
                 return response()->json(['error' => 'QR Code Expired'], 410);
            }
            return response()->json(['member' => $member]);
        }
        
        return response()->json(['error' => 'Member not found'], 404);
    }

    public function searchBookPage(Request $request)
    {
        $searchTerm = $request->input('search');
        $memberId = $request->input('member_id');
        $member = Member::find($memberId);

        if (!$member) {
            return redirect()->route('Peminjaman.search')->with('error', 'Member tidak ditemukan.');
        }

        $books = collect();

        if ($searchTerm) {
            $books = Book::with(['category', 'rack', 'bookStock'])
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

        return view('Peminjaman.searchBook', compact('books', 'member', 'memberId'));
    }

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

        $book = Book::with('bookStock')->find($bookId);
        $member = Member::find($memberId); // Ambil data member untuk log

        if (!$book) {
            return redirect()->back()->with('error', 'Buku tidak ditemukan.');
        }

        // 1. Periksa buku yang sama
        $existingLoan = Peminjaman::where('member_id', $memberId)
            ->where('book_id', $bookId)
            ->whereNull('return_date')
            ->first();

        if ($existingLoan) {
            return redirect()->back()->with('error', 'Anggota sudah meminjam buku yang sama.');
        }

        // 2. Periksa batas maksimal
        $borrowedBooksCount = Peminjaman::where('member_id', $memberId)
            ->whereNull('return_date')
            ->count();
        
        if ($borrowedBooksCount >= 3) {
            return redirect()->back()->with('error', 'Anggota sudah mencapai batas maksimal 3 buku peminjaman.');
        }

        // 3. Periksa stok
        if ($book->bookStock->jmlh_tersedia <= 0) {
            return redirect()->back()->with('error', 'Buku tidak tersedia untuk dipinjam.');
        }

        try {
            DB::beginTransaction();

            $uniqueCode = 'PJMN-' . strtoupper(Str::random(5));
            while (Peminjaman::where('resi_pjmn', $uniqueCode)->exists()) {
                $uniqueCode = 'PJMN-' . strtoupper(Str::random(5));
            }

            Peminjaman::create([
                'resi_pjmn' => $uniqueCode,
                'member_id' => $memberId,
                'book_id' => $bookId,
                'return_date' => null,
            ]);

            $bookStock = $book->bookStock;
            $bookStock->jmlh_tersedia -= 1;
            $bookStock->save();

            DB::commit();

            // --- REKAM LOG ---
            ActivityLog::record(
                'BORROW',
                "Peminjaman Buku: {$book->title}",
                [
                    'peminjam' => $member->first_name . ' ' . $member->last_name,
                    'resi' => $uniqueCode,
                    'isbn' => $book->isbn
                ]
            );
            // -----------------

            return redirect()->route('peminjaman')->with('success', 'Buku berhasil dipinjam.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan. Gagal meminjam buku.');
        }
    }

    public function destroy($id)
    {
        $peminjaman = Peminjaman::with(['book', 'member'])->find($id); // Eager load
        if (!$peminjaman) {
            return redirect()->back()->with('error', 'Data peminjaman tidak ditemukan.');
        }

        // Simpan info untuk log sebelum dihapus
        $logInfo = [
            'resi' => $peminjaman->resi_pjmn,
            'buku' => $peminjaman->book->title ?? 'Unknown Book',
            'peminjam' => $peminjaman->member->first_name ?? 'Unknown Member'
        ];

        try {
            DB::beginTransaction();

            $bookStock = $peminjaman->book->bookStock;
            $bookStock->jmlh_tersedia += 1;
            $bookStock->save();

            $peminjaman->delete();

            DB::commit();

            // --- REKAM LOG ---
            ActivityLog::record(
                'DELETE',
                "Menghapus Data Peminjaman (Resi: {$logInfo['resi']})",
                $logInfo
            );
            // -----------------

            return redirect()->back()->with('success', 'Data peminjaman berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus data peminjaman.');
        }
    }
}