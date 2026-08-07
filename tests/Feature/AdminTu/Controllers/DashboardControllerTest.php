<?php

namespace Tests\Feature\AdminTu\Controllers;

use App\Models\Operator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected()
    {
        $response = $this->get(route('tu.dashboard'));
        $response->assertRedirect('/login');
    }

    public function test_admin_tu_can_access_dashboard()
    {
        $operator = Operator::factory()->create(['role' => 'SMP']);

        $response = $this->actingAs($operator, 'operator')
            ->get(route('tu.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('tu.index');
    }
}
