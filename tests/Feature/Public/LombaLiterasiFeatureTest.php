<?php

namespace Tests\Feature\Public;

use App\Models\Operator;
use App\Models\Post;
use App\Models\Setting;
use Tests\TestCase;

class LombaLiterasiFeatureTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Operator::firstOrCreate(['username' => 'admin'], [
            'name' => 'Admin TU',
            'password' => bcrypt('password'),
            'role_operator' => 'operator',
        ]);

        Post::firstOrCreate(['slug' => 'lomba-literasi-antar-kelas-bulan-bahasa-2026'], [
            'title' => 'Lomba Literasi Antar Kelas Bulan Bahasa 2026',
            'content' => 'Lomba Cerdas Cermat Bahasa Sebagai Pemersatu Bangsa Pahlawanku https://forms.gle/frfZEwZ9x2xwuiTM9 0853465489992 Isi Formulir Pendaftaran (Google Form)',
            'category' => 'Kegiatan',
            'is_published' => true,
        ]);
    }

    protected function refreshSiteSettings(): void
    {
        view()->share('site_settings', Setting::pluck('value', 'key')->toArray());
    }

    public function test_landing_page_displays_lomba_literasi_banner_and_modal_when_active(): void
    {
        Setting::updateOrCreate(['key' => 'popup_active'], ['value' => '1']);
        $this->refreshSiteSettings();

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Lomba Literasi Antar Kelas');
        $response->assertSee('https://forms.gle/frfZEwZ9x2xwuiTM9');
        $response->assertSee('literasiModal');
    }

    public function test_landing_page_hides_modal_when_inactive(): void
    {
        Setting::updateOrCreate(['key' => 'popup_active'], ['value' => '0']);
        $this->refreshSiteSettings();

        $response = $this->get('/');

        $response->assertOk();
        $response->assertDontSee('literasiModal');

        // Restore back to active
        Setting::updateOrCreate(['key' => 'popup_active'], ['value' => '1']);
        $this->refreshSiteSettings();
    }

    public function test_news_detail_displays_full_information_and_cta(): void
    {
        $post = Post::where('slug', 'lomba-literasi-antar-kelas-bulan-bahasa-2026')->first();
        $this->assertNotNull($post);

        $response = $this->get(route('public.berita.show', $post->slug));

        $response->assertOk();
        $response->assertSee('Lomba Cerdas Cermat');
        $response->assertSee('Bahasa Sebagai Pemersatu Bangsa');
        $response->assertSee('Pahlawanku');
        $response->assertSee('https://forms.gle/frfZEwZ9x2xwuiTM9');
        $response->assertSee('0853465489992');
        $response->assertSee('Isi Formulir Pendaftaran (Google Form)');
    }

    public function test_perpustakaan_page_displays_lomba_literasi_highlight(): void
    {
        Setting::updateOrCreate(['key' => 'popup_active'], ['value' => '1']);
        $this->refreshSiteSettings();

        $response = $this->get(route('public.perpustakaan'));

        $response->assertOk();
        $response->assertSee('Lomba Literasi Antar Kelas');
        $response->assertSee('https://forms.gle/frfZEwZ9x2xwuiTM9');
    }

    public function test_admin_tu_can_update_popup_settings(): void
    {
        $operator = Operator::first();
        $this->assertNotNull($operator);

        $response = $this->actingAs($operator, 'operator')->put(route('tu.settings.update.website'), [
            'popup_active' => '1',
            'popup_title' => 'Lomba Kreativitas Pelajar',
            'popup_btn_text' => 'Daftar Sekarang',
            'popup_btn_url' => 'https://forms.gle/test-custom-link',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('settings', [
            'key' => 'popup_title',
            'value' => 'Lomba Kreativitas Pelajar',
        ]);

        // Restore default title
        Setting::updateOrCreate(['key' => 'popup_title'], ['value' => 'Lomba Literasi Antar Kelas']);
        Setting::updateOrCreate(['key' => 'popup_btn_url'], ['value' => 'https://forms.gle/frfZEwZ9x2xwuiTM9']);
        $this->refreshSiteSettings();
    }
}
