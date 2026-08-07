<?php

namespace Tests\Unit\Api\Actions\Auth;

use App\Actions\Api\Auth\ParentLoginAction;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ParentLoginActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_parent_can_login_with_correct_credentials()
    {
        $student = Student::factory()->create([
            'nis' => '123456',
            'parent_password' => Hash::make('ortupassword'),
        ]);

        $action = new ParentLoginAction;
        $result = $action->execute('123456', 'ortupassword');

        $this->assertTrue($result['success']);
        $this->assertEquals(200, $result['status_code']);
        $this->assertTrue($result['data']['is_parent']);
        $this->assertArrayHasKey('token', $result['data']);
        $this->assertEquals($student->id, $result['data']['student']->id);
    }

    public function test_parent_login_fails_when_no_parent_password_set()
    {
        Student::factory()->create([
            'nis' => '123456',
            'parent_password' => null,
        ]);

        $action = new ParentLoginAction;
        $result = $action->execute('123456', 'ortupassword');

        $this->assertFalse($result['success']);
        $this->assertEquals(401, $result['status_code']);
    }

    public function test_parent_login_fails_with_wrong_password()
    {
        Student::factory()->create([
            'nis' => '123456',
            'parent_password' => Hash::make('ortupassword'),
        ]);

        $action = new ParentLoginAction;
        $result = $action->execute('123456', 'wrongpassword');

        $this->assertFalse($result['success']);
        $this->assertEquals(401, $result['status_code']);
    }
}
