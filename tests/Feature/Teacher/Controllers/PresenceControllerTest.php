<?php

namespace Tests\Feature\Teacher\Controllers;

use Tests\TestCase;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PresenceControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_can_access_kiosk_presence_page()
    {
        $response = $this->get(route('presensi.index'));
        $response->assertStatus(200);
        $response->assertViewIs('presence');
    }

    public function test_store_presence_fails_with_invalid_credentials()
    {
        $response = $this->postJson(route('presensi.store'), [
            'identity' => 'UnknownUser',
            'password' => 'wrongpass',
            'image' => 'data:image/jpeg;base64,dummy'
        ]);

        $response->assertStatus(200); // Because Action returns 200 JSON with error status
        $response->assertJson([
            'status' => 'error',
            'message' => 'Data guru tidak ditemukan.'
        ]);
    }
}
