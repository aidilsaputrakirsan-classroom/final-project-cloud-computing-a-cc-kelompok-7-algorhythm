<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Import Activity Log
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Book extends Model
{
    use HasFactory, LogsActivity; // Tambahkan LogsActivity

    protected $table = 'tbl_books';

    protected $fillable = [
        'book_cover',
        'title',
        'author',
        'publisher',
        'isbn',
        'year',
        'rack_id',
        'category_id',
        'description'
    ];

    // Konfigurasi Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Buku '{$this->title}' telah di-{$eventName}");
    }

    public function category()
    {
        return $this->belongsTo(Kategori::class, 'category_id');
    }

    public function rack()
    {
        return $this->belongsTo(Rack::class, 'rack_id');
    }

    public function bookStock()
    {
        return $this->hasOne(BookStock::class);
    }

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'book_id');
    }
    
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorite_books')->withTimestamps();
    }
}