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
        Schema::create('graduations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->string('nisn')->nullable()->index();
            $table->string('nis')->index();
            $table->string('student_name');
            $table->date('birth_date')->nullable();
            $table->string('birth_place')->nullable();
            $table->string('classroom_name')->nullable();
            $table->string('letter_number')->nullable();
            $table->enum('status', ['lulus', 'ditunda', 'tidak_lulus'])->default('lulus');
            $table->decimal('average_score', 5, 2)->default(0);
            $table->json('scores_json')->nullable();
            $table->date('graduation_date')->nullable();
            $table->string('verification_code', 32)->unique();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('graduations');
    }
};
