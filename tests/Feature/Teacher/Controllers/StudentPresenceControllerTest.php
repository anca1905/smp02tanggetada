<?php

namespace Tests\Feature\Teacher\Controllers;

use App\Models\Classroom;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentPresenceControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_teacher_can_access_student_presence_page()
    {
        $teacher = Teacher::factory()->create();

        $response = $this->actingAs($teacher, 'teacher')->get('/teacher/student-attendance');

        $response->assertStatus(200);
        $response->assertViewIs('student_presence');
    }

    public function test_store_student_attendance_success()
    {
        $teacher = Teacher::factory()->create();
        $classroom = Classroom::factory()->create();
        $student = Student::factory()->create([
            'classroom_id' => $classroom->id,
            'nis' => '12345',
        ]);

        $response = $this->actingAs($teacher, 'teacher')->post('/teacher/student-attendance/store', [
            'class' => $classroom->id,
            'date' => Carbon::today()->format('Y-m-d'),
            'attendance' => [
                '12345' => ['status' => 'present'],
            ],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    public function test_generate_qr_success()
    {
        $teacher = Teacher::factory()->create();
        $classroom = Classroom::factory()->create();

        $response = $this->actingAs($teacher, 'teacher')->postJson('/teacher/student-attendance/generate-qr', [
            'class' => $classroom->id,
            'date' => Carbon::today()->format('Y-m-d'),
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['success', 'qr_token', 'expires_at', 'class', 'date']);
    }
}
