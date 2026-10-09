<?php

namespace Tests\Feature\Teacher\Controllers;

use App\Models\Classroom;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PromotionControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_without_classroom_can_access_promotion_with_empty_students()
    {
        $teacher = Teacher::factory()->create(); // No classroom assigned

        $response = $this->actingAs($teacher, 'teacher')
            ->get(route('teacher.promotion'));

        $response->assertStatus(200);
        $response->assertViewIs('teacher.promotion');
        $response->assertViewHas('students', function ($students) {
            return $students->isEmpty();
        });
    }

    public function test_process_promotion_redirects_back_with_success()
    {
        $teacher = Teacher::factory()->create();
        $oldClass = Classroom::factory()->create();
        $newClass = Classroom::factory()->create();

        $student = Student::factory()->create(['classroom_id' => $oldClass->id]);

        $response = $this->actingAs($teacher, 'teacher')
            ->post(route('teacher.promotion.store'), [
                'next_classroom_id' => $newClass->id,
                'action' => [
                    $student->nis => 'Naik',
                ],
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Data kenaikan kelas berhasil diproses!');
    }

    public function test_auto_detects_next_level_matching_classroom(): void
    {
        $teacher = Teacher::factory()->create();
        $class8A = Classroom::factory()->create(['name' => 'VIII A', 'level' => '8', 'teacher_id' => $teacher->id]);
        $class9A = Classroom::factory()->create(['name' => 'IX A', 'level' => '9']);
        $class9B = Classroom::factory()->create(['name' => 'IX B', 'level' => '9']);
        $student = Student::factory()->create(['classroom_id' => $class8A->id, 'student_status' => 'Active']);

        $response = $this->actingAs($teacher, 'teacher')
            ->get(route('teacher.promotion'));

        $response->assertStatus(200);
        $response->assertViewHas('targetClassroom', function ($target) use ($class9A) {
            return $target && $target->id === $class9A->id;
        });
        $response->assertSee('Naik ke Tingkat 9');
        $response->assertSee('IX A');
    }

    public function test_grade_9_is_treated_as_final_grade(): void
    {
        $teacher = Teacher::factory()->create();
        $class9 = Classroom::factory()->create(['name' => 'IX A', 'level' => '9', 'teacher_id' => $teacher->id]);

        $response = $this->actingAs($teacher, 'teacher')
            ->get(route('teacher.promotion'));

        $response->assertStatus(200);
        $response->assertViewHas('isFinalGrade', true);
        $response->assertSee('adalah Tingkat Akhir');
    }
}
