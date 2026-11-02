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
        Schema::create('library_books', function (Blueprint $table) {
            $table->id();
            $table->string('book_code')->unique();
            $table->string('title');
            $table->string('author')->nullable();
            $table->string('publisher')->nullable();
            $table->string('publish_year', 10)->nullable();
            $table->string('isbn', 50)->nullable();
            $table->string('category')->default('buku_paket_bos');
            $table->string('class_level', 10)->default('semua');
            $table->integer('total_stock')->default(1);
            $table->integer('available_stock')->default(1);
            $table->string('cover_image')->nullable();
            $table->string('location_shelf')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('library_books');
    }
};
