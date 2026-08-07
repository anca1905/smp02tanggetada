<?php

namespace Tests\Unit\AdminTu\Actions\Student;

use Tests\TestCase;
use App\Models\Student;
use App\Models\Classroom;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Actions\Student\CreateStudentAction;
use App\Actions\Student\DeleteStudentAction;

class StudentActionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_student()
    {
        $classroom = Classroom::factory()->create();
        
        $action = new CreateStudentAction();
        $student = $action->execute([
            'nis' => '120999',
            'student_name' => 'Ahmad Baru',
            'gender' => 'M',
            'classroom_id' => $classroom->id,
            'student_status' => 'Active',
            'phone_number' => '081234567890',
            'password' => 'password123'
        ]);

        $this->assertEquals('Ahmad Baru', $student->student_name);
        $this->assertDatabaseHas('students', ['nis' => '120999']);
    }

    public function test_it_deletes_student()
    {
        $student = Student::factory()->create();

        $action = new DeleteStudentAction();
        $action->execute($student);

        $this->assertDatabaseMissing('students', ['id' => $student->id]);
    }
}
