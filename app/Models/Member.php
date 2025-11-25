<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
// Import Activity Log
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Member extends Model
{
    use HasFactory, LogsActivity; // Tambahkan LogsActivity

    protected $table = 'tbl_members';

    protected $fillable = [
        'id',
        'user_id',
        'first_name',
        'last_name',
        'email',
        'imageProfile',
        'phone',
        'address', 
        'tgl_lahir',
        'last_login',
        'qr_code',
        'created_at',
    ];

    // Konfigurasi Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Member '{$this->first_name} {$this->last_name}' telah di-{$eventName}");
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'member_id');
    }

    public function favoriteBooks()
    {
        return $this->belongsToMany(Book::class, 'favorite_books')->withTimestamps();
    }
    
    public function isQrCodeExpired()
    {
        $updatedAt = $this->updated_at;
        $now = Carbon::now();
        $timeDifference = $now->diffInMinutes($updatedAt);

        return $timeDifference > 1;
    }
}