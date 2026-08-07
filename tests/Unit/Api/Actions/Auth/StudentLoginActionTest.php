<?php

namespace Tests\Unit\Api\Actions\Auth;

use App\Actions\Api\Auth\StudentLoginAction;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class StudentLoginActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_login_with_correct_credentials()
    {
        $student = Student::factory()->create([
            'nis' => '123456',
            'password' => Hash::make('password123'),
        ]);

        $action = new StudentLoginAction;
        $result = $action->execute('123456', 'password123');

        $this->assertTrue($result['success']);
        $this->assertEquals(200, $result['status_code']);
        $this->assertArrayHasKey('student', $result['data']);
        $this->assertArrayHasKey('token', $result['data']);
        $this->assertEquals($student->id, $result['data']['student']->id);
    }

    public function test_student_login_fails_with_incorrect_password()
    {
        Student::factory()->create([
            'nis' => '123456',
            'password' => Hash::make('password123'),
        ]);

        $action = new StudentLoginAction;
        $result = $action->execute('123456', 'wrongpassword');

        $this->assertFalse($result['success']);
        $this->assertEquals(401, $result['status_code']);
        $this->assertEquals('NIS atau Password salah', $result['message']);
    }

    public function test_student_login_fails_with_invalid_nis()
    {
        $action = new StudentLoginAction;
        $result = $action->execute('999999', 'password123');

        $this->assertFalse($result['success']);
        $this->assertEquals(401, $result['status_code']);
    }
}
