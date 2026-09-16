<?php

namespace Tests\Feature\AdminTu;

use App\Models\Operator;
use App\Models\Ppdb;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PpdbManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_tu_can_view_ppdb_index_with_search_and_status_filter(): void
    {
        $operator = Operator::factory()->create();

        $applicant1 = Ppdb::factory()->create([
            'nama_lengkap' => 'Ahmad Dahlan',
            'status_pendaftaran' => 'Pending',
        ]);

        $applicant2 = Ppdb::factory()->create([
            'nama_lengkap' => 'Siti Walidah',
            'status_pendaftaran' => 'Accepted',
        ]);

        // 1. View all
        $response = $this->actingAs($operator, 'operator')->get(route('tu.ppdb.index'));
        $response->assertOk();
        $response->assertSee('Ahmad Dahlan');
        $response->assertSee('Siti Walidah');

        // 2. Filter by status: Accepted
        $responseFilter = $this->actingAs($operator, 'operator')->get(route('tu.ppdb.index', ['status' => 'Accepted']));
        $responseFilter->assertOk();
        $responseFilter->assertSee('Siti Walidah');
        $responseFilter->assertDontSee('Ahmad Dahlan');

        // 3. Search by name
        $responseSearch = $this->actingAs($operator, 'operator')->get(route('tu.ppdb.index', ['search' => 'Ahmad']));
        $responseSearch->assertOk();
        $responseSearch->assertSee('Ahmad Dahlan');
        $responseSearch->assertDontSee('Siti Walidah');
    }

    public function test_admin_tu_can_update_applicant_status(): void
    {
        $operator = Operator::factory()->create();
        $ppdb = Ppdb::factory()->create([
            'status_pendaftaran' => 'Pending',
        ]);

        $response = $this->actingAs($operator, 'operator')->post(route('tu.ppdb.updateStatus', $ppdb->id), [
            'status_pendaftaran' => 'Accepted',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals('Accepted', $ppdb->fresh()->status_pendaftaran);

        // Reject status
        $responseReject = $this->actingAs($operator, 'operator')->post(route('tu.ppdb.updateStatus', $ppdb->id), [
            'status_pendaftaran' => 'Rejected',
        ]);

        $responseReject->assertRedirect();
        $this->assertEquals('Rejected', $ppdb->fresh()->status_pendaftaran);
    }

    public function test_admin_tu_can_delete_applicant_and_clean_up_files_from_storage(): void
    {
        Storage::fake('public');

        $ijazah = UploadedFile::fake()->create('ijazah.pdf', 100, 'application/pdf');
        $ijazahPath = $ijazah->store('ppdb_documents', 'public');

        $photo = UploadedFile::fake()->image('photo.jpg');
        $photoPath = $photo->store('ppdb_documents', 'public');

        $operator = Operator::factory()->create();
        $ppdb = Ppdb::factory()->create([
            'doc_ijazah' => $ijazahPath,
            'doc_pas_photo' => $photoPath,
        ]);

        Storage::disk('public')->assertExists($ijazahPath);
        Storage::disk('public')->assertExists($photoPath);

        $response = $this->actingAs($operator, 'operator')->delete(route('tu.ppdb.destroy', $ppdb->id));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('ppdb', ['id' => $ppdb->id]);

        // Assert files are deleted from storage
        Storage::disk('public')->assertMissing($ijazahPath);
        Storage::disk('public')->assertMissing($photoPath);
    }

    public function test_admin_tu_can_toggle_ppdb_open_status(): void
    {
        Setting::updateOrCreate(['key' => 'buka_ppdb'], ['value' => '1']);

        $operator = Operator::factory()->create();

        // 1. Toggle to closed
        $response = $this->actingAs($operator, 'operator')->post(route('tu.ppdb.toggleStatus'));
        $response->assertRedirect();

        $this->assertEquals('0', Setting::where('key', 'buka_ppdb')->value('value'));

        // 2. Toggle back to open
        $responseOpen = $this->actingAs($operator, 'operator')->post(route('tu.ppdb.toggleStatus'));
        $responseOpen->assertRedirect();

        $this->assertEquals('1', Setting::where('key', 'buka_ppdb')->value('value'));
    }
}
