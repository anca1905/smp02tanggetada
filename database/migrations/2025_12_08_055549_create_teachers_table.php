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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id('teacher_id');
            $table->string('name', 100);
            $table->enum('gender', ['Male', 'Female']);
            $table->string('ID', 20)->comment('NIP/NUPTK');
            $table->string('subject', 100)->nullable();
            $table->string('homeroom_class', 50)->nullable()->comment('Example: Class 12 Homeroom Teacher');
            $table->enum('status', ['Active', 'Inactive'])->default('Active');
            $table->string('username', 50)->unique();
            $table->string('password', 255);
            $table->string('photo_url', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
