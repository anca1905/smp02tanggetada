<?php

namespace Tests\Feature\AdminTu;

use App\Models\Classroom;
use App\Models\CounselingCategory;
use App\Models\Operator;
use App\Models\Student;
use App\Models\StudentCounselingRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CounselingTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_counseling(): void
    {
        $response = $this->get(route('tu.counseling.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_admin_tu_can_view_counseling_index_page(): void
    {
        $operator = Operator::factory()->create();

        $response = $this->actingAs($operator, 'operator')
            ->get(route('tu.counseling.index'));

        $response->assertOk();
        $response->assertSee('Modul Bimbingan Konseling');
    }

    public function test_admin_tu_can_store_counseling_record(): void
    {
        $operator = Operator::factory()->create();
        $classroom = Classroom::factory()->create(['name' => 'VII A']);
        $student = Student::factory()->create(['classroom_id' => $classroom->id]);
        $category = CounselingCategory::factory()->create([
            'name' => 'Terlambat Masuk Sekolah',
            'type' => 'violation',
            'point' => 5,
        ]);

        $payload = [
            'student_id' => $student->id,
            'counseling_category_id' => $category->id,
            'title' => 'Terlambat 15 menit',
            'type' => 'violation',
            'point' => 5,
            'incident_date' => now()->format('Y-m-d'),
            'description' => 'Terjebak macet di perjalanan',
            'action_taken' => 'Teguran lisan & pembinaan',
            'status' => 'resolved',
            'reported_by' => 'Guru Piket',
        ];

        $response = $this->actingAs($operator, 'operator')
            ->post(route('tu.counseling.record.store'), $payload);

        $response->assertRedirect(route('tu.counseling.index', ['tab' => 'records']));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('student_counseling_records', [
            'student_id' => $student->id,
            'title' => 'Terlambat 15 menit',
            'point' => 5,
        ]);
    }

    public function test_admin_tu_can_update_counseling_record(): void
    {
        $operator = Operator::factory()->create();
        $record = StudentCounselingRecord::factory()->create([
            'title' => 'Kasus Awal',
            'status' => 'pending',
        ]);

        $payload = [
            'student_id' => $record->student_id,
            'title' => 'Kasus Diperbarui Selesai',
            'type' => $record->type,
            'point' => $record->point,
            'incident_date' => $record->incident_date->format('Y-m-d'),
            'status' => 'resolved',
            'action_taken' => 'Telah dilakukan mediasi bersama orang tua',
        ];

        $response = $this->actingAs($operator, 'operator')
            ->put(route('tu.counseling.record.update', $record), $payload);

        $response->assertRedirect(route('tu.counseling.index', ['tab' => 'records']));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('student_counseling_records', [
            'id' => $record->id,
            'title' => 'Kasus Diperbarui Selesai',
            'status' => 'resolved',
        ]);
    }

    public function test_admin_tu_can_delete_counseling_record(): void
    {
        $operator = Operator::factory()->create();
        $record = StudentCounselingRecord::factory()->create();

        $response = $this->actingAs($operator, 'operator')
            ->delete(route('tu.counseling.record.destroy', $record));

        $response->assertRedirect(route('tu.counseling.index', ['tab' => 'records']));
        $this->assertDatabaseMissing('student_counseling_records', ['id' => $record->id]);
    }

    public function test_admin_tu_can_store_and_delete_counseling_category(): void
    {
        $operator = Operator::factory()->create();

        $payload = [
            'name' => 'Membawa HP Tanpa Izin',
            'type' => 'violation',
            'point' => 10,
            'description' => 'Membawa gawai tanpa izin guru',
        ];

        $response = $this->actingAs($operator, 'operator')
            ->post(route('tu.counseling.category.store'), $payload);

        $response->assertRedirect(route('tu.counseling.index', ['tab' => 'categories']));
        $this->assertDatabaseHas('counseling_categories', ['name' => 'Membawa HP Tanpa Izin']);

        $cat = CounselingCategory::where('name', 'Membawa HP Tanpa Izin')->first();

        $delResponse = $this->actingAs($operator, 'operator')
            ->delete(route('tu.counseling.category.destroy', $cat));

        $delResponse->assertRedirect(route('tu.counseling.index', ['tab' => 'categories']));
        $this->assertDatabaseMissing('counseling_categories', ['id' => $cat->id]);
    }
}
