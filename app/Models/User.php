<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Book; // <--- PENTING: Import model Book agar tidak error

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * Helper untuk cek role admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Relasi Many-to-Many untuk Bookmark (Koleksi Saya)
     * User bisa menyimpan banyak buku
     */
    public function bookmarks()
    {
        // Parameter: Model Tujuan, Nama Tabel Pivot, FK User, FK Buku
        return $this->belongsToMany(Book::class, 'bookmarks', 'user_id', 'book_id')->withTimestamps();
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}