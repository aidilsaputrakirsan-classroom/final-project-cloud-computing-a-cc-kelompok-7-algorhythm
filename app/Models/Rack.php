<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// Import Activity Log
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Rack extends Model
{
    use HasFactory, SoftDeletes, LogsActivity; // Tambahkan LogsActivity

    protected $table = 'tbl_racks';

    protected $fillable = ['id','name', 'rak'];

    // Konfigurasi Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Rak '{$this->name}' telah di-{$eventName}");
    }

    public function books()
    {
        return $this->hasMany(Book::class, 'rack_id');
    }
}