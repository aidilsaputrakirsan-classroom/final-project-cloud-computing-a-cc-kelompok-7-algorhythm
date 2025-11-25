<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// 1. Import Library Activity Log (Wajib ada)
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
    // 2. Tambahkan trait LogsActivity
    use HasFactory, Notifiable, LogsActivity;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // <--- Wajib ada untuk sistem Multi-Role Anda
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // 3. Konfigurasi Activity Log (VERSI PERBAIKAN)
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            // PERBAIKAN UTAMA: Gunakan 'logExcept', bukan 'dontLogAttribute'
            ->logExcept(['password', 'remember_token']) 
            ->setDescriptionForEvent(fn(string $eventName) => "User '{$this->name}' telah di-{$eventName}");
    }

    // 4. Helper untuk Cek Admin (Wajib ada untuk Middleware IsAdmin)
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}