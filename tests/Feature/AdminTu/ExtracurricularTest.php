<?php

namespace Tests\Feature\AdminTu;

use App\Models\Extracurricular;
use App\Models\ExtracurricularRegistration;
use App\Models\Operator;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExtracurricularTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_extracurriculars(): void
    {
        $response = $this->get(route('tu.extracurriculars.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_admin_tu_can_view_extracurriculars_index(): void
    {
        $operator = Operator::factory()->create();

        $response = $this->actingAs($operator, 'operator')
            ->get(route('tu.extracurriculars.index'));

        $response->assertOk();
        $response->assertSee('Ekstrakurikuler');
    }

    public function test_admin_tu_can_store_extracurricular(): void
    {
        $operator = Operator::factory()->create();

        $payload = [
            'name' => 'Klub Robotik dan Coding',
            'category' => 'akademik',
            'instructor_name' => 'Ahmad Fauzi, S.Kom.',
            'schedule_day' => 'Rabu, 15:30 WITA',
            'location' => 'Laboratorium Komputer',
            'description' => 'Pembinaan logika pemrograman dan mikrokontroler',
            'is_active' => 1,
        ];

        $response = $this->actingAs($operator, 'operator')
            ->post(route('tu.extracurriculars.store'), $payload);

        $response->assertRedirect(route('tu.extracurriculars.index', ['tab' => 'list']));
        $this->assertDatabaseHas('extracurriculars', [
            'name' => 'Klub Robotik dan Coding',
            'category' => 'akademik',
        ]);
    }

    public function test_admin_tu_can_update_extracurricular(): void
    {
        $operator = Operator::factory()->create();
        $ekskul = Extracurricular::factory()->create([
            'name' => 'Nama Awal',
            'category' => 'olahraga',
        ]);

        $payload = [
            'name' => 'Nama Diperbarui',
            'category' => 'seni_budaya',
            'instructor_name' => 'Pelatih Baru',
            'is_active' => 1,
        ];

        $response = $this->actingAs($operator, 'operator')
            ->put(route('tu.extracurriculars.update', $ekskul), $payload);

        $response->assertRedirect(route('tu.extracurriculars.index', ['tab' => 'list']));
        $this->assertDatabaseHas('extracurriculars', [
            'id' => $ekskul->id,
            'name' => 'Nama Diperbarui',
            'category' => 'seni_budaya',
        ]);
    }

    public function test_admin_tu_can_store_registration_manually(): void
    {
        $operator = Operator::factory()->create();
        $ekskul = Extracurricular::factory()->create();
        $student = Student::factory()->create();

        $payload = [
            'extracurricular_id' => $ekskul->id,
            'student_id' => $student->id,
            'student_name' => $student->student_name,
            'classroom_name' => 'Kelas VII-A',
            'phone' => '081298765432',
            'reason' => 'Ingin aktif berprestasi di ekskul ini',
        ];

        $response = $this->actingAs($operator, 'operator')
            ->post(route('tu.extracurriculars.registrations.store'), $payload);

        $response->assertRedirect(route('tu.extracurriculars.index', ['tab' => 'registrations']));
        $this->assertDatabaseHas('extracurricular_registrations', [
            'extracurricular_id' => $ekskul->id,
            'student_name' => $student->student_name,
            'status' => 'accepted',
        ]);
    }

    public function test_admin_tu_can_update_registration_status(): void
    {
        $operator = Operator::factory()->create();
        $reg = ExtracurricularRegistration::factory()->create(['status' => 'pending']);

        $response = $this->actingAs($operator, 'operator')
            ->patch(route('tu.extracurriculars.registrations.status', $reg), [
                'status' => 'accepted',
                'notes' => 'Disetujui pembina ekskul',
            ]);

        $response->assertRedirect(route('tu.extracurriculars.index', ['tab' => 'registrations']));
        $this->assertEquals('accepted', $reg->fresh()->status);
    }

    public function test_public_user_can_view_extracurricular_showcase(): void
    {
        $ekskul = Extracurricular::factory()->create([
            'name' => 'Pramuka Penggalang Tanggetada',
            'is_active' => true,
        ]);

        $response = $this->get(route('public.ekstrakurikuler'));
        $response->assertOk();
        $response->assertSee('Pramuka Penggalang Tanggetada');
    }

    public function test_public_user_can_submit_registration(): void
    {
        $ekskul = Extracurricular::factory()->create(['is_active' => true]);

        $payload = [
            'extracurricular_id' => $ekskul->id,
            'student_name' => 'Siswa Baru Berbakat',
            'classroom_name' => 'Kelas VII-C',
            'phone' => '085233445566',
            'reason' => 'Sangat tertarik mengikuti pembinaan rutin',
        ];

        $response = $this->post(route('public.ekstrakurikuler.store'), $payload);
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('extracurricular_registrations', [
            'extracurricular_id' => $ekskul->id,
            'student_name' => 'Siswa Baru Berbakat',
            'status' => 'pending',
        ]);
    }
}
