<?php

namespace Tests\Unit\Api\Actions\Student;

use App\Actions\Api\Student\GetStudentGradesAction;
use App\Models\Student;
use App\Models\StudentGrade;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetStudentGradesActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_grades_summary_and_distribution_are_calculated_correctly()
    {
        $student = Student::factory()->create();

        // 95 (A), 85 (B), 75 (C), 65 (D)
        // Average: (95 + 85 + 75 + 65) / 4 = 320 / 4 = 80
        StudentGrade::factory()->create(['student_id' => $student->id, 'score' => 95]);
        StudentGrade::factory()->create(['student_id' => $student->id, 'score' => 85]);
        StudentGrade::factory()->create(['student_id' => $student->id, 'score' => 75]);
        StudentGrade::factory()->create(['student_id' => $student->id, 'score' => 65]);

        $action = new GetStudentGradesAction;
        $result = $action->execute($student);

        $this->assertTrue($result['success']);

        $summary = $result['data']['summary'];
        $this->assertEquals(80, $summary['average']);
        $this->assertEquals(95, $summary['highest']);
        $this->assertEquals(65, $summary['lowest']);
        $this->assertEquals(4, $summary['total_subjects']);

        $distribution = $result['data']['distribution'];
        $this->assertEquals(1, $distribution['A']);
        $this->assertEquals(1, $distribution['B']);
        $this->assertEquals(1, $distribution['C']);
        $this->assertEquals(1, $distribution['D']);
    }
}
