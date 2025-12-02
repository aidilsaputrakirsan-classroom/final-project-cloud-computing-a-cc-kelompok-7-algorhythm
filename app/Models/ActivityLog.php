<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'action', 'description', 'details'];

    // Relasi ke User (Untuk kolom User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Fungsi Helper Statis untuk Merekam Log dengan mudah
    public static function record($action, $description, $details = null)
    {
        self::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => $description,
            'details' => is_array($details) ? json_encode($details) : $details,
        ]);
    }
}