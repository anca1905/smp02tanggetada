<?php

namespace Tests\Feature;

use App\Models\Setting;
use Tests\TestCase;

class DeveloperPortalTest extends TestCase
{
    public function test_developer_gateway_shows_login_screen(): void
    {
        $response = $this->get('/skillance-control');

        $response->assertStatus(200);
        $response->assertSee('Skillance Gateway');
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
    }

    public function test_developer_auth_fails_with_wrong_key(): void
    {
        $response = $this->from('/skillance-control')->post('/skillance-control/auth', [
            'secret_key' => 'wrong-passphrase',
        ]);

        $response->assertRedirect('/skillance-control');
        $response->assertSessionHasErrors('secret_key');
    }

    public function test_developer_auth_succeeds_with_valid_key(): void
    {
        $masterKey = (string) config('app.skillance_dev_key');

        $response = $this->post('/skillance-control/auth', [
            'secret_key' => $masterKey,
        ]);

        $response->assertRedirect('/skillance-control/dashboard');
        $this->assertTrue(session('skillance_dev_authenticated'));
    }

    public function test_feature_gate_aborts_404_when_feature_disabled(): void
    {
        Setting::updateOrCreate(['key' => 'feature_spp_billing'], ['value' => '0']);
        Setting::updateOrCreate(['key' => 'feature_absensi_guru'], ['value' => '0']);

        $operator = \App\Models\Operator::create([
            'name' => 'Operator Test',
            'username' => uniqid('op_gate_'),
            'password' => bcrypt('password'),
            'role' => 'SMP',
        ]);

        // Testing direct access while disabled
        $responseBilling = $this->actingAs($operator, 'operator')->get('/tu/billing');
        $this->assertEquals(404, $responseBilling->getStatusCode());

        $responseTeacherAttendance = $this->actingAs($operator, 'operator')->get('/tu/teacher-attendance');
        $this->assertEquals(404, $responseTeacherAttendance->getStatusCode());
    }

    public function test_sidebar_displays_sims_pro_module_section(): void
    {
        Setting::updateOrCreate(['key' => 'feature_absensi_guru'], ['value' => '0']);
        Setting::updateOrCreate(['key' => 'feature_spp_billing'], ['value' => '0']);

        $operator = \App\Models\Operator::create([
            'name' => 'Operator Sidebar Test',
            'username' => uniqid('op_side_'),
            'password' => bcrypt('password'),
            'role' => 'SMP',
        ]);

        $response = $this->actingAs($operator, 'operator')->get('/tu/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Kelulusan & SKL', false);
        $response->assertSee('Presensi Guru');
        $response->assertSee('Tagihan & SPP Siswa', false);
        $response->assertSee('WhatsApp Gateway');
        $response->assertSee('CBT Ujian Online');
        $response->assertSee('simsProModal');
    }

    public function test_favicon_renders_uploaded_school_logo(): void
    {
        Setting::updateOrCreate(
            ['key' => 'school_logo'],
            ['value' => 'logos/smp2_custom_logo.png']
        );

        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('storage/logos/smp2_custom_logo.png');
    }

    public function test_favicon_renders_fallback_when_logo_empty(): void
    {
        Setting::updateOrCreate(
            ['key' => 'school_logo'],
            ['value' => null]
        );

        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Logo_of_Ministry_of_Education_and_Culture_of_Republic_of_Indonesia.svg');
    }
}
