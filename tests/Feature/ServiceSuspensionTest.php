<?php

namespace Tests\Feature;

use App\Models\Setting;
use Tests\TestCase;

class ServiceSuspensionTest extends TestCase
{
    protected function tearDown(): void
    {
        // Pastikan setelah test selesai, setting billing_suspended tetap '1' sesuai status aktif yang diminta user
        Setting::updateOrCreate(['key' => 'billing_suspended'], ['value' => '1']);
        config(['app.billing_suspended' => true]);

        parent::tearDown();
    }

    public function test_when_suspended_public_routes_return_503_and_display_notice(): void
    {
        Setting::updateOrCreate(['key' => 'billing_suspended'], ['value' => '1']);
        config(['app.billing_suspended' => true]);

        $response = $this->get('/');

        $response->assertStatus(503);
        $response->assertSee('LAYANAN SISTEM DITANGGUHKAN SEMENTARA');
        $response->assertSee('INV-2026/XI-SIMS/001');
        $response->assertSee('05 November 2026');
        $response->assertSee('SeaBank');
        $response->assertSee('901376460015');
        $response->assertSee('Muh Arsyad Ramsi');
        $response->assertSee('Rp 400.000,-');
        $response->assertSee('082291700778');
        $response->assertSee('https://wa.me/6282291700778', false);
    }

    public function test_when_suspended_login_routes_return_503(): void
    {
        Setting::updateOrCreate(['key' => 'billing_suspended'], ['value' => '1']);
        config(['app.billing_suspended' => true]);

        $response = $this->get('/login');

        $response->assertStatus(503);
        $response->assertSee('LAYANAN SISTEM DITANGGUHKAN SEMENTARA');
    }

    public function test_when_suspended_api_routes_return_503_json(): void
    {
        Setting::updateOrCreate(['key' => 'billing_suspended'], ['value' => '1']);
        config(['app.billing_suspended' => true]);

        $response = $this->postJson('/api/student/login', [
            'nis' => '120001',
            'password' => 'password',
        ]);

        $response->assertStatus(503);
        $response->assertJson([
            'status' => false,
            'code' => 'SERVICE_SUSPENDED',
        ]);
    }

    public function test_skillance_developer_control_remains_accessible_during_suspension(): void
    {
        Setting::updateOrCreate(['key' => 'billing_suspended'], ['value' => '1']);
        config(['app.billing_suspended' => true]);

        $response = $this->get('/skillance-control');

        $response->assertStatus(200);
        $response->assertSee('Skillance Gateway');
    }

    public function test_dokumen_routes_remain_accessible_during_suspension(): void
    {
        Setting::updateOrCreate(['key' => 'billing_suspended'], ['value' => '1']);
        config(['app.billing_suspended' => true]);

        $response = $this->get('/dokumen/invoice-smp2-tanggetada.html');

        $response->assertStatus(200);
        $this->assertFileExists($response->getFile()->getPathname());
    }

    public function test_developer_can_toggle_suspension_status(): void
    {
        $masterKey = (string) config('app.skillance_dev_key');

        // Login as developer
        $this->post('/skillance-control/auth', [
            'secret_key' => $masterKey,
        ]);

        $this->assertTrue(session('skillance_dev_authenticated'));

        // Toggle to inactive (0)
        config(['app.billing_suspended' => false]);
        Setting::updateOrCreate(['key' => 'billing_suspended'], ['value' => '1']);

        $responseToggle = $this->post('/skillance-control/toggle-suspension');
        $responseToggle->assertRedirect();

        $this->assertEquals('0', Setting::where('key', 'billing_suspended')->value('value'));

        // Toggle back to active (1)
        $responseToggleBack = $this->post('/skillance-control/toggle-suspension');
        $responseToggleBack->assertRedirect();

        $this->assertEquals('1', Setting::where('key', 'billing_suspended')->value('value'));
    }

    public function test_when_suspension_is_disabled_system_functions_normally(): void
    {
        config(['app.billing_suspended' => false]);
        Setting::updateOrCreate(['key' => 'billing_suspended'], ['value' => '0']);

        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertDontSee('LAYANAN SISTEM DITANGGUHKAN SEMENTARA');
    }
}
