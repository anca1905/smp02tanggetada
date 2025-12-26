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
        Schema::create('room_borrowings', function (Blueprint $table) {
            $table->id('borrowing_id');
            $table->string('full_name', 100);
            $table->string('nis', 20)->nullable();
            $table->string('class', 10)->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->string('room_type', 50);
            $table->date('borrow_date');
            $table->text('activity_description');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('responsible_person', 100)->nullable();
            $table->enum('status', ['upcoming', 'ongoing', 'completed'])->default('upcoming');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_borrowings');
    }
};
