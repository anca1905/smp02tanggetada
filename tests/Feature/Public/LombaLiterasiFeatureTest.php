<?php

namespace Tests\Feature\Public;

use App\Models\Post;
use Tests\TestCase;

class LombaLiterasiFeatureTest extends TestCase
{
    public function test_landing_page_displays_lomba_literasi_banner_and_modal(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('LOMBA LITERASI ANTAR KELAS');
        $response->assertSee('https://forms.gle/frfZEwZ9x2xwuiTM9');
        $response->assertSee('0853465489992');
        $response->assertSee('literasiModal');
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
        $response = $this->get(route('public.perpustakaan'));

        $response->assertOk();
        $response->assertSee('EVENT BULAN BAHASA 2026');
        $response->assertSee('Lomba Literasi Antar Kelas');
        $response->assertSee('https://forms.gle/frfZEwZ9x2xwuiTM9');
        $response->assertSee('0853465489992');
    }
}
