<?php

namespace Tests\Feature\Principal\Controllers;

use App\Models\Operator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected()
    {
        $response = $this->get('/principal/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_operator_biasa_cannot_access_principal_dashboard()
    {
        $operator = Operator::factory()->create(['role_operator' => 'Admin TU']);

        $response = $this->actingAs($operator, 'operator')->get('/principal/dashboard');

        $response->assertStatus(403);
    }

    public function test_principal_can_access_dashboard()
    {
        $operator = Operator::factory()->create(['role_operator' => 'Kepala Sekolah']);

        $response = $this->actingAs($operator, 'operator')->get('/principal/dashboard');

        $response->assertStatus(200);
        $response->assertViewIs('principal.dashboard');
    }
}
