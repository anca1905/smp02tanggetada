<?php

namespace Tests\Feature\AdminTu\Controllers;

use App\Models\Operator;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SubjectControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_it_displays_subjects_list(): void
    {
        $operator = Operator::factory()->create(['role' => 'SMP']);
        Subject::create(['code' => 'BHS-INDO', 'name' => 'Bahasa Indonesia']);

        $response = $this->actingAs($operator, 'operator')
            ->get(route('tu.subjects.index'));

        $response->assertStatus(200);
        $response->assertViewIs('tu.subjects.index');
        $response->assertSee('BHS-INDO');
        $response->assertSee('Bahasa Indonesia');
    }

    public function test_it_creates_subject_with_cover(): void
    {
        $operator = Operator::factory()->create(['role' => 'SMP']);
        $cover = UploadedFile::fake()->image('buku_indo.jpg', 600, 400);

        $response = $this->actingAs($operator, 'operator')
            ->post(route('tu.subjects.store'), [
                'code' => 'BHS-INDO',
                'name' => 'Bahasa Indonesia',
                'cover' => $cover,
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('subjects', [
            'code' => 'BHS-INDO',
            'name' => 'Bahasa Indonesia',
        ]);

        $subject = Subject::where('code', 'BHS-INDO')->first();
        $this->assertNotNull($subject->cover);
        Storage::disk('public')->assertExists($subject->cover);
        $this->assertNotNull($subject->cover_url);
    }

    public function test_it_updates_subject_and_cover(): void
    {
        $operator = Operator::factory()->create(['role' => 'SMP']);
        $oldCover = UploadedFile::fake()->image('old_cover.jpg');
        $subject = Subject::create([
            'code' => 'IPA-01',
            'name' => 'Ilmu Pengetahuan Alam',
            'cover' => $oldCover->store('subjects', 'public'),
        ]);

        $newCover = UploadedFile::fake()->image('new_cover.jpg');
        $response = $this->actingAs($operator, 'operator')
            ->put(route('tu.subjects.update', $subject->id), [
                'code' => 'IPA-01',
                'name' => 'IPA Terpadu',
                'cover' => $newCover,
            ]);

        $response->assertRedirect();
        $subject->refresh();
        $this->assertSame('IPA Terpadu', $subject->name);
        Storage::disk('public')->assertExists($subject->cover);
    }

    public function test_it_deletes_subject_and_removes_cover_file(): void
    {
        $operator = Operator::factory()->create(['role' => 'SMP']);
        $cover = UploadedFile::fake()->image('cover.jpg');
        $coverPath = $cover->store('subjects', 'public');

        $subject = Subject::create([
            'code' => 'MTK-01',
            'name' => 'Matematika',
            'cover' => $coverPath,
        ]);

        Storage::disk('public')->assertExists($coverPath);

        $response = $this->actingAs($operator, 'operator')
            ->delete(route('tu.subjects.destroy', $subject->id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('subjects', ['id' => $subject->id]);
        Storage::disk('public')->assertMissing($coverPath);
    }
}
