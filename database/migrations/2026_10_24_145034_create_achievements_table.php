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
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('student_name');
            $table->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            $table->string('category')->default('akademik');
            $table->string('level')->default('kabupaten');
            $table->string('rank')->nullable();
            $table->string('event_name');
            $table->date('event_date')->nullable();
            $table->string('photo_path')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};
