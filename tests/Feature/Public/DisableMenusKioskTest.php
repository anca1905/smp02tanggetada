<?php

namespace Tests\Feature\Public;

use App\Models\Operator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DisableMenusKioskTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_does_not_display_kiosk_button(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('Kiosk Presensi');
        $response->assertDontSee(route('presensi.index'));
    }

    public function test_landing_page_does_not_display_sarana_prasarana_card(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('Sarana Prasarana');
    }

    public function test_auth_login_page_does_not_display_kiosk_or_presensi_link(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertDontSee(route('presensi.index'));
    }

    public function test_public_navbar_does_not_contain_sarana_prasarana_link(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('Sarana &amp; Prasarana', false);
        $response->assertDontSee('Sarana & Prasarana');
    }

    public function test_profile_subpages_do_not_contain_sarana_prasarana_quick_link(): void
    {
        $pages = ['/profil/sejarah', '/profil/visi-misi', '/profil/struktur-organisasi', '/profil/gtk'];

        foreach ($pages as $url) {
            $response = $this->get($url);
            $response->assertStatus(200);
            $response->assertDontSee('Sarana &amp; Prasarana', false);
            $response->assertDontSee('Sarana & Prasarana');
        }
    }

    public function test_profil_sarana_route_redirects_to_profil(): void
    {
        $response = $this->get('/profil/sarana-prasarana');

        $response->assertRedirect(route('public.profil'));
    }

    public function test_admin_tu_sidebar_does_not_display_fasilitas_sekolah(): void
    {
        $operator = Operator::factory()->create([
            'role_operator' => 'Tata Usaha',
        ]);

        $response = $this->actingAs($operator, 'operator')->get(route('tu.dashboard'));

        $response->assertStatus(200);
        $response->assertDontSee('Fasilitas Sekolah');
        $response->assertDontSee(route('tu.facility.index'));
    }
}
