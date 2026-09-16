<?php

namespace Tests\Feature\Public;

use App\Models\Ppdb;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PpdbRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_form_is_accessible_when_ppdb_is_open(): void
    {
        Setting::updateOrCreate(['key' => 'buka_ppdb'], ['value' => '1']);

        $response = $this->get('/ppdb/daftar');

        $response->assertOk();
        $response->assertViewIs('public.ppdb-form');
    }

    public function test_registration_form_shows_closed_page_when_ppdb_is_closed(): void
    {
        Setting::updateOrCreate(['key' => 'buka_ppdb'], ['value' => '0']);

        $response = $this->get('/ppdb/daftar');

        $response->assertOk();
        $response->assertViewIs('public.ppdb-closed');
    }

    public function test_registration_submission_is_rejected_when_ppdb_open_setting_is_false(): void
    {
        Setting::updateOrCreate(['key' => 'ppdb_open'], ['value' => 'false']);

        $response = $this->post('/ppdb/daftar', [
            'nama_lengkap' => 'Jane Doe',
            'nisn' => '1234567890',
            'nik' => '3201234567890001',
            'jenis_kelamin' => 'Perempuan',
            'no_hp' => '081234567890',
            'asal_sekolah' => 'SMPN 1 Jakarta',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Pendaftaran PPDB saat ini sedang ditutup.');
        $this->assertDatabaseCount('ppdb', 0);
    }

    public function test_validation_fails_when_required_fields_are_missing(): void
    {
        Setting::updateOrCreate(['key' => 'buka_ppdb'], ['value' => '1']);

        $response = $this->post('/ppdb/daftar', []);

        $response->assertRedirect();
        $response->assertSessionHasErrors([
            'nama_lengkap',
            'nisn',
            'nik',
            'jenis_kelamin',
            'no_hp',
            'asal_sekolah',
        ]);
    }

    public function test_validation_fails_when_nisn_or_nik_are_not_numeric(): void
    {
        Setting::updateOrCreate(['key' => 'buka_ppdb'], ['value' => '1']);

        $response = $this->post('/ppdb/daftar', [
            'nama_lengkap' => 'Jane Doe',
            'nisn' => 'NOTNUMERIC123',
            'nik' => 'ABC1234567890',
            'jenis_kelamin' => 'Perempuan',
            'no_hp' => '081234567890',
            'asal_sekolah' => 'SMPN 1 Jakarta',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['nisn', 'nik']);
    }

    public function test_validation_allows_valid_document_formats_and_stores_files_in_storage(): void
    {
        Storage::fake('public');
        Setting::updateOrCreate(['key' => 'buka_ppdb'], ['value' => '1']);

        $pasPhoto = UploadedFile::fake()->image('photo.jpg');
        $ijazah = UploadedFile::fake()->create('ijazah.pdf', 500, 'application/pdf');
        $kk = UploadedFile::fake()->image('kk.png');
        $akta = UploadedFile::fake()->image('akta.jpeg');

        $payload = [
            'nama_lengkap' => 'Budi Pratama',
            'nisn' => '0098765432',
            'nik' => '3201234567890002',
            'jenis_kelamin' => 'Laki-laki',
            'no_hp' => '081234567890',
            'asal_sekolah' => 'SD Negeri 1 Tanggetada',
            'doc_pas_photo' => $pasPhoto,
            'doc_ijazah' => $ijazah,
            'doc_kk' => $kk,
            'doc_akta' => $akta,
        ];

        $response = $this->post('/ppdb/daftar', $payload);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success');

        $ppdb = Ppdb::where('nisn', '0098765432')->first();
        $this->assertNotNull($ppdb);
        $this->assertEquals('BUDI PRATAMA', $ppdb->nama_lengkap);
        $this->assertEquals('L', $ppdb->jenis_kelamin);

        $this->assertNotNull($ppdb->doc_pas_photo);
        $this->assertNotNull($ppdb->doc_ijazah);
        $this->assertNotNull($ppdb->doc_kk);
        $this->assertNotNull($ppdb->doc_akta);

        Storage::disk('public')->assertExists($ppdb->doc_pas_photo);
        Storage::disk('public')->assertExists($ppdb->doc_ijazah);
        Storage::disk('public')->assertExists($ppdb->doc_kk);
        Storage::disk('public')->assertExists($ppdb->doc_akta);
    }

    public function test_registration_numbers_are_auto_incrementing_and_unique(): void
    {
        Setting::updateOrCreate(['key' => 'buka_ppdb'], ['value' => '1']);

        $payload1 = [
            'nama_lengkap' => 'Applicant One',
            'nisn' => '1111111111',
            'nik' => '3201000000000001',
            'jenis_kelamin' => 'Laki-laki',
            'no_hp' => '081234567891',
            'asal_sekolah' => 'SD 1',
        ];

        $payload2 = [
            'nama_lengkap' => 'Applicant Two',
            'nisn' => '2222222222',
            'nik' => '3201000000000002',
            'jenis_kelamin' => 'Perempuan',
            'no_hp' => '081234567892',
            'asal_sekolah' => 'SD 2',
        ];

        $this->post('/ppdb/daftar', $payload1)->assertRedirect()->assertSessionHasNoErrors();
        $this->post('/ppdb/daftar', $payload2)->assertRedirect()->assertSessionHasNoErrors();

        $student1 = Ppdb::where('nisn', '1111111111')->first();
        $student2 = Ppdb::where('nisn', '2222222222')->first();

        $year = date('Y');
        $this->assertMatchesRegularExpression("/^REG-{$year}-\d{4}$/", $student1->no_registrasi);
        $this->assertMatchesRegularExpression("/^REG-{$year}-\d{4}$/", $student2->no_registrasi);
        $this->assertNotEquals($student1->no_registrasi, $student2->no_registrasi);
    }
}
