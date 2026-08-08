<?php

namespace Tests\Unit\Student\Actions\Learning;

use App\Actions\Student\Learning\GetStudentCoursesAction;
use App\Models\Classroom;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class GetStudentCoursesActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_empty_when_student_has_no_class()
    {
        $student = Student::factory()->create(['classroom_id' => null]);
        Auth::guard('student')->login($student);

        $action = new GetStudentCoursesAction;
        $result = $action->execute();

        $this->assertEmpty($result);
    }

    public function test_it_returns_courses_grouped_by_subject_when_student_has_class()
    {
        $classroom = Classroom::factory()->create();
        $student = Student::factory()->create(['classroom_id' => $classroom->id]);
        Auth::guard('student')->login($student);

        $subject = Subject::factory()->create(['name' => 'Matematika']);
        $teacher = Teacher::factory()->create();

        Schedule::factory()->create([
            'classroom_id' => $classroom->id,
            'subject_id' => $subject->id,
            'teacher_id' => $teacher->id,
        ]);

        $action = new GetStudentCoursesAction;
        $result = $action->execute();

        $this->assertTrue($result['myCourses']->has('Matematika'));
        $this->assertEquals(1, $result['myCourses']['Matematika']->count());
    }
}
