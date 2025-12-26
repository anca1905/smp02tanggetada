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
        Schema::create('students', function (Blueprint $table) {
            $table->string('nis', 20)->primary(); 
            $table->string('student_name', 100);
            $table->enum('gender', ['M', 'F']);
            $table->string('class', 10);
            $table->string('phone_number', 25)->nullable();
            $table->enum('student_status', ['Active', 'Graduated', 'Inactive'])->default('Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
