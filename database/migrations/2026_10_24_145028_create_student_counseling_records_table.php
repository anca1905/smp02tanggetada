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
        Schema::create('student_counseling_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('counseling_category_id')->nullable()->constrained('counseling_categories')->nullOnDelete();
            $table->string('title');
            $table->enum('type', ['violation', 'achievement'])->default('violation');
            $table->integer('point')->default(0);
            $table->date('incident_date');
            $table->text('description')->nullable();
            $table->text('action_taken')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'resolved'])->default('resolved');
            $table->string('reported_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_counseling_records');
    }
};
