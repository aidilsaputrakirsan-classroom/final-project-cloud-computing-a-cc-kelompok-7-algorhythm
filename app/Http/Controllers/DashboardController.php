<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Book;
use App\Models\Kategori; // <-- INI DIA PERBAIKANNYA (sebelumnya 'Category')
use App\Models\Rack;
use App\Models\Peminjaman;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Menampilkan data untuk halaman dashboard.
     */
    public function index()
    {
        $today = Carbon::today();

        // 1. Data untuk "Laporan Hari Ini"
        $newMembersCountToday = Member::whereDate('created_at', $today)->count();
        $borrowingBooksCountToday = Peminjaman::whereDate('created_at', $today)->count();
        $returnBooksCountToday = Peminjaman::whereDate('return_date', $today)->count();
        
        // Menghitung total pinjaman yang telat (lewat 7 hari DAN belum kembali)
        $overdueLoansCount = Peminjaman::where('created_at', '<', now()->subDays(7))
                                        ->whereNull('return_date')
                                        ->count();

        // 2. Data untuk "Master Data" (Pengganti Finansial)
        $totalMembers = Member::count();
        $totalBooks = Book::count();
        $totalCategories = Kategori::count(); // <-- INI JUGA DIPERBAIKI (sebelumnya 'Category')
        $totalRacks = Rack::count();

        // 3. Data untuk Chart (Peminjaman 7 hari terakhir)
        $chartLabels = [];
        $chartData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartLabels[] = $date->format('D, d M'); // Format: "Wed, 05 Nov"
            $chartData[] = Peminjaman::whereDate('created_at', $date)->count();
        }

        // Mengirim semua data ke view
        return view('dashboard', compact(
            'newMembersCountToday',
            'borrowingBooksCountToday',
            'returnBooksCountToday',
            'overdueLoansCount',
            'totalMembers',
            'totalBooks',
            'totalCategories',
            'totalRacks',
            'chartLabels',
            'chartData'
        ));
    }
}