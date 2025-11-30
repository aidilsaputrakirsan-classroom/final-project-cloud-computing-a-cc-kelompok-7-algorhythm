<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('bookmarks', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        
        // PERBAIKAN: Gunakan nama tabel yang benar 'tbl_books'
        $table->foreignId('book_id')->constrained('tbl_books')->onDelete('cascade');
        
        $table->timestamps();
        
        // Update unique constraint juga jika diperlukan
        $table->unique(['user_id', 'book_id']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookmarks');
    }
};
