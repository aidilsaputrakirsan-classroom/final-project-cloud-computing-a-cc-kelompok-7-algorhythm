<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// Import Activity Log
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Peminjaman extends Model
{
    use HasFactory, SoftDeletes, LogsActivity; // Tambahkan LogsActivity

    protected $table = 'tbl_peminjaman';

    protected $fillable = [
        'resi_pjmn',
        'member_id',
        'book_id',
        'return_date',
    ];

    // Konfigurasi Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Peminjaman (Resi: {$this->resi_pjmn}) telah di-{$eventName}");
    }

    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id'); 
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
}