<?php

namespace Tests\Feature\Auth;

use App\Models\Operator;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class LoginProtectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_access_login_pages(): void
    {
        $this->get('/login')->assertOk()->assertViewIs('auth.login');
        $this->get('/login/admin-tu')->assertOk()->assertViewIs('auth.login-admin-tu');
        $this->get('/login/pegawai')->assertOk()->assertViewIs('auth.login-pegawai');
        $this->get('/login/kepala-sekolah')->assertOk()->assertViewIs('auth.login-kepala-sekolah');
    }

    public function test_authenticated_operator_admin_tu_is_redirected_to_tu_dashboard_from_login_pages(): void
    {
        $operator = Operator::factory()->create([
            'role_operator' => 'Admin TU',
        ]);

        $this->actingAs($operator, 'operator');

        $this->get('/login')->assertRedirect(route('tu.dashboard'));
        $this->get('/login/admin-tu')->assertRedirect(route('tu.dashboard'));
        $this->get('/login/pegawai')->assertRedirect(route('tu.dashboard'));
        $this->get('/login/kepala-sekolah')->assertRedirect(route('tu.dashboard'));
    }

    public function test_authenticated_operator_kepala_sekolah_is_redirected_to_principal_dashboard_from_login_pages(): void
    {
        $operator = Operator::factory()->create([
            'role_operator' => 'Kepala Sekolah',
        ]);

        $this->actingAs($operator, 'operator');

        $this->get('/login')->assertRedirect(route('principal.dashboard'));
        $this->get('/login/admin-tu')->assertRedirect(route('principal.dashboard'));
        $this->get('/login/pegawai')->assertRedirect(route('principal.dashboard'));
        $this->get('/login/kepala-sekolah')->assertRedirect(route('principal.dashboard'));
    }

    public function test_authenticated_teacher_is_redirected_to_teacher_dashboard_from_login_pages(): void
    {
        $teacher = Teacher::factory()->create();

        $this->actingAs($teacher, 'teacher');

        $this->get('/login')->assertRedirect(route('teacher.dashboard'));
        $this->get('/login/admin-tu')->assertRedirect(route('teacher.dashboard'));
        $this->get('/login/pegawai')->assertRedirect(route('teacher.dashboard'));
        $this->get('/login/kepala-sekolah')->assertRedirect(route('teacher.dashboard'));
    }

    public function test_authenticated_student_is_redirected_to_student_dashboard_from_login_pages(): void
    {
        $student = Student::factory()->create();

        $this->actingAs($student, 'student');

        $this->get('/login')->assertRedirect(route('student.dashboard'));
        $this->get('/login/admin-tu')->assertRedirect(route('student.dashboard'));
        $this->get('/login/pegawai')->assertRedirect(route('student.dashboard'));
        $this->get('/login/kepala-sekolah')->assertRedirect(route('student.dashboard'));
    }

    public function test_landing_page_shows_login_button_when_guest(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee(route('login'));
        $response->assertDontSee(route('logout'));
    }

    public function test_landing_page_shows_dashboard_and_logout_when_authenticated_as_operator(): void
    {
        $operator = Operator::factory()->create([
            'role_operator' => 'Admin TU',
        ]);

        $this->actingAs($operator, 'operator');

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee(route('tu.dashboard'));
        $response->assertSee(route('logout'));
    }

    public function test_landing_page_shows_dashboard_and_logout_when_authenticated_as_teacher(): void
    {
        $teacher = Teacher::factory()->create();

        $this->actingAs($teacher, 'teacher');

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee(route('teacher.dashboard'));
        $response->assertSee(route('logout'));
    }

    public function test_landing_page_shows_dashboard_and_logout_when_authenticated_as_student(): void
    {
        $student = Student::factory()->create();

        $this->actingAs($student, 'student');

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee(route('student.dashboard'));
        $response->assertSee(route('logout'));
    }

    public function test_logout_logs_out_all_active_guards(): void
    {
        $operator = Operator::factory()->create();
        $teacher = Teacher::factory()->create();

        $this->actingAs($operator, 'operator');
        $this->actingAs($teacher, 'teacher');

        $this->assertTrue(Auth::guard('operator')->check());
        $this->assertTrue(Auth::guard('teacher')->check());

        $response = $this->post('/logout');

        $response->assertRedirect(route('login'));
        $this->assertFalse(Auth::guard('operator')->check());
        $this->assertFalse(Auth::guard('teacher')->check());
        $this->assertFalse(Auth::guard('student')->check());
    }
}
