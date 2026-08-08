<?php

namespace Tests\Feature\Teacher\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GraduationControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_can_access_graduation_page()
    {
        $teacher = Teacher::factory()->create([
            'homeroom_class' => '12 A',
        ]);

        $response = $this->actingAs($teacher, 'teacher')->get('/teacher/graduation');

        $response->assertStatus(200);
        $response->assertViewIs('teacher.graduation');
    }

    public function test_store_graduation_success()
    {
        $teacher = Teacher::factory()->create();
        $student = Student::factory()->create([
            'nis' => '12345',
            'student_status' => 'Active',
        ]);

        $response = $this->actingAs($teacher, 'teacher')->post('/teacher/graduation', [
            'status' => [
                '12345' => 'Lulus',
            ],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'student_status' => 'Graduated',
        ]);
    }
}
