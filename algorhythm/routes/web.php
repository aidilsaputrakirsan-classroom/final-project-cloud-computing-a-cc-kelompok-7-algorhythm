<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
});

// routes/web.php
use App\Http\Controllers\BukuController;

// ... route lain

Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');