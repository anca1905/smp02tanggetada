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
        Schema::create('student_attendance_details', function (Blueprint $table) {
            $table->id('student_attendance_detail_id');
            $table->unsignedBigInteger('attendance_id');
            $table->string('nis', 20);
            $table->enum('status', ['present', 'sick', 'permission', 'absent']);
            $table->timestamps();
            $table->foreign('attendance_id')->references('attendance_id')->on('attendances')->onDelete('cascade');
            $table->foreign('nis')->references('nis')->on('students')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_attendance_details');
    }
};
