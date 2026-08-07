<?php

namespace Tests\Feature\Api\Controllers;

use App\Models\Bill;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BillingApiControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_student_bills_endpoint()
    {
        $student = Student::factory()->create();

        Bill::factory()->create([
            'student_id' => $student->id,
            'amount' => 100000,
            'status' => 'unpaid',
        ]);

        $response = $this->actingAs($student)->getJson('/api/student/bills');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'summary',
                    'active_bills',
                    'history_bills',
                ],
            ]);
    }
}
