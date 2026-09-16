<?php

namespace Tests\Feature\AdminTu;

use App\Models\Operator;
use App\Models\Ppdb;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PpdbDocumentAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_ppdb_document(): void
    {
        $ppdb = Ppdb::factory()->create([
            'doc_ijazah' => 'ppdb_documents/sample.pdf',
        ]);

        $response = $this->get(route('tu.ppdb.document', [$ppdb->id, 'doc_ijazah']));

        $response->assertRedirect(route('login'));
    }

    public function test_non_operator_cannot_access_ppdb_document(): void
    {
        $teacher = Teacher::factory()->create();
        $ppdb = Ppdb::factory()->create([
            'doc_ijazah' => 'ppdb_documents/sample.pdf',
        ]);

        $this->actingAs($teacher, 'teacher');

        $response = $this->get(route('tu.ppdb.document', [$ppdb->id, 'doc_ijazah']));

        $response->assertRedirect(route('login'));
    }

    public function test_operator_can_view_valid_document_from_public_disk(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('ijazah.pdf', 100, 'application/pdf');
        $path = $file->store('ppdb_documents', 'public');

        $operator = Operator::factory()->create();
        $ppdb = Ppdb::factory()->create([
            'doc_ijazah' => $path,
        ]);

        $this->actingAs($operator, 'operator');

        $response = $this->get(route('tu.ppdb.document', [$ppdb->id, 'doc_ijazah']));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_operator_can_view_valid_document_from_local_disk_fallback(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        $file = UploadedFile::fake()->create('kk.pdf', 100, 'application/pdf');
        $path = $file->store('ppdb_documents', 'local');

        $operator = Operator::factory()->create();
        $ppdb = Ppdb::factory()->create([
            'doc_kk' => $path,
        ]);

        $this->actingAs($operator, 'operator');

        $response = $this->get(route('tu.ppdb.document', [$ppdb->id, 'doc_kk']));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_document_access_aborts_404_for_unauthorized_field(): void
    {
        $operator = Operator::factory()->create();
        $ppdb = Ppdb::factory()->create();

        $this->actingAs($operator, 'operator');

        $response = $this->get(route('tu.ppdb.document', [$ppdb->id, 'password']));

        $response->assertNotFound();
    }

    public function test_document_access_aborts_404_if_field_is_empty(): void
    {
        $operator = Operator::factory()->create();
        $ppdb = Ppdb::factory()->create([
            'doc_ijazah' => null,
        ]);

        $this->actingAs($operator, 'operator');

        $response = $this->get(route('tu.ppdb.document', [$ppdb->id, 'doc_ijazah']));

        $response->assertNotFound();
    }

    public function test_document_access_aborts_404_if_file_missing_on_server(): void
    {
        Storage::fake('public');
        Storage::fake('local');

        $operator = Operator::factory()->create();
        $ppdb = Ppdb::factory()->create([
            'doc_ijazah' => 'ppdb_documents/non_existent.pdf',
        ]);

        $this->actingAs($operator, 'operator');

        $response = $this->get(route('tu.ppdb.document', [$ppdb->id, 'doc_ijazah']));

        $response->assertNotFound();
    }

    public function test_ppdb_show_view_renders_protected_document_route_links(): void
    {
        $operator = Operator::factory()->create();
        $ppdb = Ppdb::factory()->create([
            'doc_ijazah' => 'ppdb_documents/sample.pdf',
        ]);

        $this->actingAs($operator, 'operator');

        $response = $this->get(route('tu.ppdb.show', $ppdb->id));

        $response->assertOk();
        $response->assertSee(route('tu.ppdb.document', [$ppdb->id, 'doc_ijazah']));
    }
}
