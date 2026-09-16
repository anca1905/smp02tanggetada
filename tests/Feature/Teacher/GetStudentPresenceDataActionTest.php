<?php

namespace Tests\Feature\Teacher;

use App\Actions\Teacher\StudentPresence\GetStudentPresenceDataAction;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class GetStudentPresenceDataActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_subjects_and_defaults_subject_id()
    {
        $teacher = Teacher::factory()->create();
        Auth::guard('teacher')->login($teacher);

        $subject1 = Subject::factory()->create(['name' => 'Math']);
        $subject2 = Subject::factory()->create(['name' => 'Physics']);

        $academicYear = AcademicYear::factory()->create(['is_active' => true]);
        $classroom = Classroom::factory()->create(['academic_year_id' => $academicYear->id]);

        Schedule::factory()->create([
            'teacher_id' => $teacher->id,
            'subject_id' => $subject1->id,
            'classroom_id' => $classroom->id,
        ]);
        Schedule::factory()->create([
            'teacher_id' => $teacher->id,
            'subject_id' => $subject2->id,
            'classroom_id' => $classroom->id,
        ]);

        $action = new GetStudentPresenceDataAction;

        $data = $action->execute($classroom->id, Carbon::today()->format('Y-m-d'), 'kelas', null);

        $this->assertCount(2, $data['subjects']);
        $this->assertEquals($subject1->id, $data['selectedSubject']); // Auto defaults to first subject
    }

    public function test_it_does_not_default_subject_id_if_session_not_kelas()
    {
        $teacher = Teacher::factory()->create();
        Auth::guard('teacher')->login($teacher);

        $subject1 = Subject::factory()->create(['name' => 'Math']);

        $academicYear = AcademicYear::factory()->create(['is_active' => true]);
        $classroom = Classroom::factory()->create(['academic_year_id' => $academicYear->id]);

        Schedule::factory()->create([
            'teacher_id' => $teacher->id,
            'subject_id' => $subject1->id,
            'classroom_id' => $classroom->id,
        ]);

        $action = new GetStudentPresenceDataAction;

        $data = $action->execute($classroom->id, Carbon::today()->format('Y-m-d'), 'apel', null);

        $this->assertNull($data['selectedSubject']);
    }
}
