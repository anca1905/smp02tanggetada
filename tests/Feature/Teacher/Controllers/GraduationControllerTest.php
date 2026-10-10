<?php

namespace Tests\Feature\Teacher\Controllers;

use App\Models\Classroom;
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
            'homeroom_class' => 'IX B',
        ]);

        $classroom = Classroom::factory()->create([
            'name' => 'IX B',
            'level' => '9',
            'teacher_id' => $teacher->id,
        ]);

        $students = Student::factory()->count(10)->create([
            'classroom_id' => $classroom->id,
            'student_status' => 'Active',
        ]);

        $response = $this->actingAs($teacher, 'teacher')->get('/teacher/graduation');

        $response->assertStatus(200);
        $response->assertViewIs('teacher.graduation');
        $response->assertSee('IX B');
        $response->assertSee('Total Siswa: <span class="font-bold">10</span>', false);
        $response->assertSee($students->first()->student_name);
    }

    public function test_teacher_with_homeroom_class_string_without_direct_relation_loads_students()
    {
        $otherTeacher = Teacher::factory()->create();
        $teacher = Teacher::factory()->create([
            'homeroom_class' => 'IX B',
        ]);

        $classroom = Classroom::factory()->create([
            'name' => 'IX B',
            'level' => '9',
            'teacher_id' => $otherTeacher->id,
        ]);

        $student = Student::factory()->create([
            'classroom_id' => $classroom->id,
            'student_status' => 'Active',
            'student_name' => 'Ahmad Siswa',
        ]);

        $response = $this->actingAs($teacher, 'teacher')->get('/teacher/graduation');

        $response->assertStatus(200);
        $response->assertSee('IX B');
        $response->assertSee('Ahmad Siswa');
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
