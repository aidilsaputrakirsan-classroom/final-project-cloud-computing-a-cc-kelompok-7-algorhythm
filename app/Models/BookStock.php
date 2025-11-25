<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Import Activity Log
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class BookStock extends Model
{
    use HasFactory, LogsActivity; // Tambahkan LogsActivity

    protected $table = 'tbl_book_stock';

    protected $fillable = [
        'book_id',
        'jmlh_tersedia'
    ];

    // Konfigurasi Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Stok Buku (ID: {$this->book_id}) telah di-{$eventName}");
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
}