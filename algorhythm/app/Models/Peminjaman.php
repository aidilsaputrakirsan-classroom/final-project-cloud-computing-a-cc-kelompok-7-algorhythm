<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Peminjaman extends Model
{
    use HasFactory, SoftDeletes;

    // Tentukan nama tabel
    protected $table = 'tbl_peminjaman';

    // Tentukan field yang boleh diisi
    protected $fillable = [
        'resi_pjmn',
        'member_id',
        'book_id',
        'return_date',
    ];

    /**
     * Relasi ke Member
     * Ini penting agar $peminjaman->member->first_name di 'daftarpeminjaman.blade.php' berfungsi
     */
    public function member()
    {
        // Pastikan nama model Member Anda adalah Member
        return $this->belongsTo(Member::class, 'member_id'); 
    }

    /**
     * Relasi ke Buku
     * Ini penting agar $peminjaman->book->title di 'daftarpeminjaman.blade.php' berfungsi
     */
    public function book()
    {
        // Pastikan nama model Buku Anda adalah Book
        return $this->belongsTo(Book::class, 'book_id');
    }
}
