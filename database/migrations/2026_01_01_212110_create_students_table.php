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
            $table->id(); 
            $table->string('nis', 20)->unique(); 
            $table->string('student_name', 100);
            $table->string('password'); 
            $table->enum('gender', ['M', 'F']);
            $table->foreignId('classroom_id')->nullable()->constrained()->nullOnDelete();
            $table->string('phone_number', 25)->nullable();
            $table->string('email')->nullable()->unique(); 
            $table->string('place_of_birth')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('student_status', ['Active', 'Graduated', 'Inactive'])->default('Active');
            $table->rememberToken();
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
