<?php

namespace Tests\Feature\Public\Controllers;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_access_landing_page()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('landing');
    }

    public function test_guest_can_access_news_page()
    {
        $response = $this->get('/berita');

        $response->assertStatus(200);
        $response->assertViewIs('public.news.news');
    }

    public function test_guest_can_access_calendar_page()
    {
        $response = $this->get('/kalender');

        $response->assertStatus(200);
        $response->assertViewIs('public.calender');
    }

    public function test_post_contact_with_valid_data_redirects_back_with_success()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'Tanya',
            'message' => 'Halo',
        ];

        $response = $this->post('/kontak', $data);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('messages', ['email' => 'john@example.com']);
    }

    public function test_post_contact_without_data_redirects_back_with_errors()
    {
        $response = $this->post('/kontak', []);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['name', 'email', 'subject', 'message']);
    }

    public function test_post_ppdb_with_valid_data_redirects_with_registration_number_in_session()
    {
        Setting::updateOrCreate(['key' => 'buka_ppdb'], ['value' => '1']);

        $data = [
            'nama_lengkap' => 'Jane Doe',
            'nisn' => '1234567890',
            'nik' => '3201234567890001',
            'jenis_kelamin' => 'Perempuan',
            'jurusan' => 'RPL',
            'no_hp' => '081234567890',
            'asal_sekolah' => 'SMPN 1 Jakarta',
        ];

        $response = $this->post('/ppdb/daftar', $data);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Assert that the success message contains "REG-"
        $this->assertStringContainsString('REG-', session('success'));

        $this->assertDatabaseHas('ppdb', ['nama_lengkap' => 'JANE DOE']);
    }

    public function test_post_ppdb_when_closed_redirects_back_with_error()
    {
        Setting::updateOrCreate(['key' => 'buka_ppdb'], ['value' => '0']);

        $response = $this->post('/ppdb/daftar', [
             'nama_lengkap' => 'Jane Doe',
             'nisn' => '1234567890',
             'nik' => '3201234567890001',
             'jenis_kelamin' => 'Perempuan',
             'jurusan' => 'RPL',
             'no_hp' => '081234567890',
             'asal_sekolah' => 'SMPN 1 Jakarta',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Pendaftaran PPDB saat ini sedang ditutup.');
    }
}
