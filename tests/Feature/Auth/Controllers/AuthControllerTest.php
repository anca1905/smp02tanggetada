<?php

namespace Tests\Feature\Auth\Controllers;

use App\Models\Operator;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_dapat_melihat_halaman_login()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    public function test_login_dengan_kredensial_valid_redirect_ke_dashboard_yang_sesuai()
    {
        $operator = Operator::factory()->create([
            'username' => 'admin_tu',
            'password' => bcrypt('password123'),
            'role_operator' => 'Admin TU',
        ]);

        $response = $this->post('/login', [
            'username' => 'admin_tu',
            'password' => 'password123',
            'role_type' => 'operator',
        ]);

        $response->assertRedirect(route('tu.dashboard'));
        $this->assertAuthenticatedAs($operator, 'operator');
    }

    public function test_login_dengan_kredensial_valid_sebagai_kepsek_redirect_ke_principal_dashboard()
    {
        $operator = Operator::factory()->create([
            'username' => 'kepsek_test',
            'password' => bcrypt('password123'),
            'role_operator' => 'Kepala Sekolah',
        ]);

        $response = $this->post('/login', [
            'username' => 'kepsek_test',
            'password' => 'password123',
            'role_type' => 'operator',
        ]);

        $response->assertRedirect(route('principal.dashboard'));
        $this->assertAuthenticatedAs($operator, 'operator');
    }

    public function test_login_dengan_kredensial_valid_sebagai_teacher_redirect_ke_teacher_dashboard()
    {
        $teacher = Teacher::factory()->create([
            'username' => 'guru_test',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'username' => 'guru_test',
            'password' => 'password123',
            'role_type' => 'teacher',
        ]);

        $response->assertRedirect(route('teacher.dashboard'));
        $this->assertAuthenticatedAs($teacher, 'teacher');
    }

    public function test_login_dengan_kredensial_salah_redirect_back_dengan_error_username()
    {
        $operator = Operator::factory()->create([
            'username' => 'admin_tu',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'username' => 'admin_tu',
            'password' => 'wrong_password',
            'role_type' => 'operator',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['username']);
        $this->assertGuest('operator');
    }

    public function test_logout_menghapus_session_dan_redirect_ke_login()
    {
        $operator = Operator::factory()->create([
            'username' => 'admin_tu',
            'password' => bcrypt('password123'),
        ]);

        $this->actingAs($operator, 'operator');

        $response = $this->post('/logout');

        $response->assertRedirect(route('login'));
        $this->assertGuest('operator');
    }
}
