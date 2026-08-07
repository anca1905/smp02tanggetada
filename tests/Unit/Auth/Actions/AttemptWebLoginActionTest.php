<?php

namespace Tests\Unit\Auth\Actions;

use App\Actions\Auth\AttemptWebLoginAction;
use App\Models\Operator;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AttemptWebLoginActionTest extends TestCase
{
    use RefreshDatabase;

    protected AttemptWebLoginAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new AttemptWebLoginAction;
    }

    public function test_login_berhasil_sebagai_operator_tu()
    {
        $operator = Operator::factory()->create([
            'username' => 'admin',
            'password' => bcrypt('password'),
            'role_operator' => 'Admin TU',
        ]);

        $route = $this->action->execute('admin', 'password', 'operator');

        $this->assertEquals('tu.dashboard', $route);
        $this->assertTrue(Auth::guard('operator')->check());
        $this->assertEquals($operator->operator_id, Auth::guard('operator')->id());
    }

    public function test_login_berhasil_sebagai_kepala_sekolah()
    {
        $principal = Operator::factory()->create([
            'username' => 'kepsek',
            'password' => bcrypt('password'),
            'role_operator' => 'Kepala Sekolah',
        ]);

        $route = $this->action->execute('kepsek', 'password', 'operator');

        $this->assertEquals('principal.dashboard', $route);
        $this->assertTrue(Auth::guard('operator')->check());
        $this->assertEquals($principal->operator_id, Auth::guard('operator')->id());
    }

    public function test_login_berhasil_sebagai_teacher()
    {
        $teacher = Teacher::factory()->create([
            'username' => 'guru1',
            'password' => bcrypt('password'),
        ]);

        $route = $this->action->execute('guru1', 'password', 'teacher');

        $this->assertEquals('teacher.dashboard', $route);
        $this->assertTrue(Auth::guard('teacher')->check());
        $this->assertEquals($teacher->id, Auth::guard('teacher')->id());
    }

    public function test_login_gagal_karena_password_salah()
    {
        $operator = Operator::factory()->create([
            'username' => 'admin',
            'password' => bcrypt('password'),
        ]);

        $route = $this->action->execute('admin', 'wrong_password', 'operator');

        $this->assertFalse($route);
        $this->assertFalse(Auth::guard('operator')->check());
    }
}
