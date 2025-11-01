<?php
// app/Models/Rack.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rack extends Model
{
    use HasFactory, SoftDeletes;

    // Menentukan nama tabel yang sesuai
    protected $table = 'tbl_racks';

    // Menentukan kolom yang dapat diisi (mass assignable)
    protected $fillable = ['id', 'name', 'rak'];

}