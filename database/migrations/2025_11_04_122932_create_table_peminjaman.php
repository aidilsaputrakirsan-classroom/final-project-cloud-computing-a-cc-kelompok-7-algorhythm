<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tbl_peminjaman', function (Blueprint $table) {
            $table->id();
            $table->string('resi_pjmn', 20)->unique(); // Resi Peminjaman
            $table->unsignedBigInteger('member_id'); // WAJIB ada member
            $table->unsignedBigInteger('book_id'); // WAJIB ada buku
            
            // Sesuai view daftarpeminjaman, 'created_at' akan jadi 'Tanggal Pinjam'
            
            // Dibuat nullable, karena view Anda mengecek is_null($peminjaman->return_date)
            $table->dateTime('return_date')->nullable(); 
            
            $table->timestamps(); // Ini akan membuat created_at (Tanggal Pinjam)
            $table->softDeletes(); // Diambil dari referensi Anda

            // Foreign Keys (dibuat non-nullable)
            $table->foreign('member_id')->references('id')->on('tbl_members')->onDelete('cascade');
            $table->foreign('book_id')->references('id')->on('tbl_books')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_peminjaman');
    }
};