<?php

namespace Tests\Feature\AdminTu;

use App\Models\Classroom;
use App\Models\Operator;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StudentPhotoUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_admin_tu_can_create_student_with_photo(): void
    {
        $operator = Operator::factory()->create();
        $classroom = Classroom::factory()->create();
        $photo = UploadedFile::fake()->image('foto_siswa.jpg');

        $response = $this->actingAs($operator, 'operator')
            ->post(route('tu.student.store'), [
                'nis' => '120101',
                'student_name' => 'Budi Santoso',
                'gender' => 'M',
                'classroom_id' => $classroom->id,
                'student_status' => 'Active',
                'photo_url' => $photo,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $student = Student::where('nis', '120101')->firstOrFail();
        $this->assertNotNull($student->photo_url);
        Storage::disk('public')->assertExists($student->photo_url);
    }

    public function test_admin_tu_can_update_student_photo_and_removes_old_photo(): void
    {
        $operator = Operator::factory()->create();
        $classroom = Classroom::factory()->create();

        // Create student with initial photo
        $oldPhoto = UploadedFile::fake()->image('foto_lama.jpg');
        $oldPath = $oldPhoto->store('students/photos', 'public');

        $student = Student::factory()->create([
            'classroom_id' => $classroom->id,
            'photo_url' => $oldPath,
        ]);

        Storage::disk('public')->assertExists($oldPath);

        $newPhoto = UploadedFile::fake()->image('foto_baru.jpg');

        $response = $this->actingAs($operator, 'operator')
            ->put(route('tu.student.update', $student->nis), [
                'student_name' => 'Nama Baru',
                'gender' => 'M',
                'classroom_id' => $classroom->id,
                'student_status' => 'Active',
                'photo_url' => $newPhoto,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $student->refresh();

        // Old photo must be deleted from storage
        Storage::disk('public')->assertMissing($oldPath);

        // New photo must exist in storage and DB updated
        $this->assertNotNull($student->photo_url);
        $this->assertNotEquals($oldPath, $student->photo_url);
        Storage::disk('public')->assertExists($student->photo_url);
    }

    public function test_updating_student_without_new_photo_retains_existing_photo(): void
    {
        $operator = Operator::factory()->create();
        $classroom = Classroom::factory()->create();

        $photo = UploadedFile::fake()->image('foto_tetap.jpg');
        $photoPath = $photo->store('students/photos', 'public');

        $student = Student::factory()->create([
            'classroom_id' => $classroom->id,
            'photo_url' => $photoPath,
        ]);

        $response = $this->actingAs($operator, 'operator')
            ->put(route('tu.student.update', $student->nis), [
                'student_name' => 'Nama Diedit',
                'gender' => 'M',
                'classroom_id' => $classroom->id,
                'student_status' => 'Active',
            ]);

        $response->assertRedirect();
        $student->refresh();

        $this->assertEquals($photoPath, $student->photo_url);
        Storage::disk('public')->assertExists($photoPath);
    }

    public function test_photo_validation_rejects_non_image_and_oversized_files(): void
    {
        $operator = Operator::factory()->create();
        $classroom = Classroom::factory()->create();

        // Non-image file (e.g. PDF)
        $nonImage = UploadedFile::fake()->create('dokumen.pdf', 500, 'application/pdf');

        $response1 = $this->actingAs($operator, 'operator')
            ->post(route('tu.student.store'), [
                'nis' => '120102',
                'student_name' => 'Budi Non Image',
                'gender' => 'M',
                'classroom_id' => $classroom->id,
                'student_status' => 'Active',
                'photo_url' => $nonImage,
            ]);

        $response1->assertSessionHasErrors('photo_url');

        // Oversized image (> 2048 KB)
        $oversized = UploadedFile::fake()->image('big_photo.jpg')->size(3000);

        $response2 = $this->actingAs($operator, 'operator')
            ->post(route('tu.student.store'), [
                'nis' => '120103',
                'student_name' => 'Budi Oversized',
                'gender' => 'M',
                'classroom_id' => $classroom->id,
                'student_status' => 'Active',
                'photo_url' => $oversized,
            ]);

        $response2->assertSessionHasErrors('photo_url');
    }

    public function test_deleting_student_removes_photo_from_storage(): void
    {
        $operator = Operator::factory()->create();
        $classroom = Classroom::factory()->create();

        $photo = UploadedFile::fake()->image('foto_hapus.jpg');
        $photoPath = $photo->store('students/photos', 'public');

        $student = Student::factory()->create([
            'classroom_id' => $classroom->id,
            'photo_url' => $photoPath,
        ]);

        Storage::disk('public')->assertExists($photoPath);

        $response = $this->actingAs($operator, 'operator')
            ->delete(route('tu.student.destroy', $student->nis));

        $response->assertRedirect();
        $this->assertDatabaseMissing('students', ['id' => $student->id]);
        Storage::disk('public')->assertMissing($photoPath);
    }

    public function test_student_card_displays_uploaded_photo(): void
    {
        $operator = Operator::factory()->create();
        $classroom = Classroom::factory()->create();

        $photoPath = 'students/photos/test_card_photo.jpg';
        $student = Student::factory()->create([
            'classroom_id' => $classroom->id,
            'photo_url' => $photoPath,
        ]);

        $response = $this->actingAs($operator, 'operator')
            ->get(route('tu.student.card', $student->nis));

        $response->assertOk();
        $response->assertSee(asset('storage/'.$photoPath));
    }

    public function test_student_data_table_displays_uploaded_photo_and_multipart_form(): void
    {
        $operator = Operator::factory()->create();
        $classroom = Classroom::factory()->create();

        $photoPath = 'students/photos/table_student_photo.jpg';
        $student = Student::factory()->create([
            'classroom_id' => $classroom->id,
            'photo_url' => $photoPath,
        ]);

        $response = $this->actingAs($operator, 'operator')
            ->get(route('tu.student.index'));

        $response->assertOk();
        $response->assertSee(asset('storage/'.$photoPath));
        $response->assertSee('enctype="multipart/form-data"', false);
    }
}
