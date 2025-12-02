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
    Schema::create('activity_logs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // Siapa yang melakukan (User)
        $table->string('action'); // Apa tindakannya (Action)
        $table->string('description'); // Penjelasan singkat (Description)
        $table->text('details')->nullable(); // Data detail json/text (Detail)
        $table->timestamps(); // Waktu kejadian (Timestamp)
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
