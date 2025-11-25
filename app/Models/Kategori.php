<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
// Import Activity Log
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Kategori extends Model
{
    use HasFactory, Notifiable, SoftDeletes, LogsActivity; // Tambahkan LogsActivity

    protected $table = 'tbl_categories';
    
    protected $fillable = [
        'id',
        'name',
    ];

    protected $dates = ['deleted_at'];

    // Konfigurasi Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Kategori '{$this->name}' telah di-{$eventName}");
    }

    public function books()
    {
        return $this->hasMany(Book::class, 'category_id');
    }

    public function bookStocks()
    {
        return $this->hasManyThrough(BookStock::class, Book::class, 'category_id', 'book_id', 'id', 'id');
    }
}