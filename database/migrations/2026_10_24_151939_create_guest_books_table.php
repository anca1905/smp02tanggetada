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
        Schema::create('guest_books', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('institution')->nullable();
            $table->string('phone');
            $table->string('category')->default('umum');
            $table->string('meet_with')->default('kepala_sekolah');
            $table->text('purpose');
            $table->date('visit_date');
            $table->string('visit_time', 10)->nullable();
            $table->enum('status', ['waiting', 'in_progress', 'completed'])->default('completed');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guest_books');
    }
};
