<?php

namespace Tests\Feature\Teacher\Controllers;

use Tests\TestCase;
use App\Models\Teacher;
use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
                                 $student->nis => 'Naik'
                             ]
                         ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Data kenaikan kelas berhasil diproses!');
    }
}
