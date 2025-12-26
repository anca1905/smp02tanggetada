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
        Schema::create('teacher_attendances', function (Blueprint $table) {
            $table->id('teacher_attendance_id');
            $table->unsignedBigInteger('teacher_id');
            $table->date('date');
            $table->time('arrival_time')->nullable();
            $table->time('return_time')->nullable();
            $table->string('arrival_photo_url', 255)->nullable();
            $table->string('return_photo_url', 255)->nullable();
            $table->timestamps();

            $table->foreign('teacher_id')->references('teacher_id')->on('teachers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_attendances');
    }
};
