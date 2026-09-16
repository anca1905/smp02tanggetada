<?php

namespace Tests\Feature\Teacher;

use App\Models\Attendance;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentPresenceSubjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_attendance_requires_subject_id_when_session_is_kelas()
    {
        $teacher = Teacher::factory()->create();
        $classroom = Classroom::factory()->create();

        // 1. Missing subject_id
        $response = $this->actingAs($teacher, 'teacher')->post('/teacher/student-attendance/store', [
            'class' => $classroom->id,
            'session_type' => 'kelas',
            'date' => Carbon::today()->format('Y-m-d'),
            'attendance' => [],
        ]);

        $response->assertSessionHasErrors('subject_id');

        // 2. Apel does not require subject_id
        $responseApel = $this->actingAs($teacher, 'teacher')->post('/teacher/student-attendance/store', [
            'class' => $classroom->id,
            'session_type' => 'apel',
            'date' => Carbon::today()->format('Y-m-d'),
            'attendance' => [],
        ]);

        $responseApel->assertSessionDoesntHaveErrors('subject_id');
    }

    public function test_generate_qr_requires_subject_id_when_session_is_kelas()
    {
        $teacher = Teacher::factory()->create();
        $classroom = Classroom::factory()->create();

        // 1. Missing subject_id
        $response = $this->actingAs($teacher, 'teacher')->postJson('/teacher/student-attendance/generate-qr', [
            'class' => $classroom->id,
            'session_type' => 'kelas',
            'date' => Carbon::today()->format('Y-m-d'),
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('subject_id');

        // 2. Apel does not require subject_id
        $responseApel = $this->actingAs($teacher, 'teacher')->postJson('/teacher/student-attendance/generate-qr', [
            'class' => $classroom->id,
            'session_type' => 'apel',
            'date' => Carbon::today()->format('Y-m-d'),
        ]);

        $responseApel->assertJsonMissingValidationErrors('subject_id');
    }

    public function test_store_attendance_saves_subject_id_and_differentiates_sessions()
    {
        $teacher = Teacher::factory()->create();
        $classroom = Classroom::factory()->create();
        $subject1 = Subject::factory()->create();
        $subject2 = Subject::factory()->create();
        $date = Carbon::today()->format('Y-m-d');

        // 1. Save attendance for subject 1
        $this->actingAs($teacher, 'teacher')->post('/teacher/student-attendance/store', [
            'class' => $classroom->id,
            'session_type' => 'kelas',
            'subject_id' => $subject1->id,
            'date' => $date,
            'attendance' => ['1234' => ['status' => 'present']],
        ])->assertSessionHasNoErrors();

        // 2. Save attendance for subject 2
        $this->actingAs($teacher, 'teacher')->post('/teacher/student-attendance/store', [
            'class' => $classroom->id,
            'session_type' => 'kelas',
            'subject_id' => $subject2->id,
            'date' => $date,
            'attendance' => ['1234' => ['status' => 'present']],
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseCount('attendances', 2);

        $this->assertDatabaseHas('attendances', [
            'class' => $classroom->id,
            'session_type' => 'kelas',
            'subject_id' => $subject1->id,
        ]);

        $this->assertDatabaseHas('attendances', [
            'class' => $classroom->id,
            'session_type' => 'kelas',
            'subject_id' => $subject2->id,
        ]);
    }
}
