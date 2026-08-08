<?php

namespace Tests\Unit\Teacher\Actions\Graduation;

use App\Actions\Teacher\Graduation\ProcessGraduationAction;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProcessGraduationActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_updates_student_status_to_graduated_when_lulus()
    {
        $student = Student::factory()->create([
            'nis' => '12345',
            'student_status' => 'Active',
        ]);

        $action = new ProcessGraduationAction;
        $action->execute([
            '12345' => 'Lulus',
        ]);

        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'student_status' => 'Graduated',
        ]);
    }

    public function test_it_keeps_student_status_active_when_tidak_lulus()
    {
        $student = Student::factory()->create([
            'nis' => '12345',
            'student_status' => 'Active',
        ]);

        $action = new ProcessGraduationAction;
        $action->execute([
            '12345' => 'Tidak Lulus',
        ]);

        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'student_status' => 'Active',
        ]);
    }
}
