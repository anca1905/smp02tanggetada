<?php

namespace Tests\Feature\AdminTu;

use App\Models\Achievement;
use App\Models\Operator;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AchievementTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_can_view_achievements_page(): void
    {
        $achievement = Achievement::factory()->create([
            'title' => 'Juara 1 OSN IPA Terpadu',
            'is_featured' => true,
        ]);

        $response = $this->get(route('public.prestasi'));

        $response->assertOk();
        $response->assertSee('Juara 1 OSN IPA Terpadu');
        $response->assertSee('Galeri Prestasi');
    }

    public function test_admin_tu_can_view_achievements_index(): void
    {
        $operator = Operator::factory()->create();

        $response = $this->actingAs($operator, 'operator')
            ->get(route('tu.achievements.index'));

        $response->assertOk();
        $response->assertSee('Galeri Prestasi');
    }

    public function test_admin_tu_can_store_achievement_with_photo(): void
    {
        Storage::fake('public');
        $operator = Operator::factory()->create();
        $student = Student::factory()->create();
        $photo = UploadedFile::fake()->image('sertifikat.jpg');

        $payload = [
            'title' => 'Juara 1 Lomba Pidato Bahasa Inggris',
            'student_name' => $student->student_name,
            'student_id' => $student->id,
            'category' => 'akademik',
            'level' => 'provinsi',
            'rank' => 'Juara 1',
            'event_name' => 'English Speech Contest SMP 2026',
            'event_date' => now()->format('Y-m-d'),
            'photo' => $photo,
            'description' => 'Meraih nilai tertinggi pada babak final',
            'is_featured' => 1,
        ];

        $response = $this->actingAs($operator, 'operator')
            ->post(route('tu.achievements.store'), $payload);

        $response->assertRedirect(route('tu.achievements.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('achievements', [
            'title' => 'Juara 1 Lomba Pidato Bahasa Inggris',
            'student_name' => $student->student_name,
            'is_featured' => true,
        ]);

        $achievement = Achievement::where('title', 'Juara 1 Lomba Pidato Bahasa Inggris')->first();
        $this->assertNotNull($achievement->photo_path);
        Storage::disk('public')->assertExists($achievement->photo_path);
    }

    public function test_admin_tu_can_update_achievement(): void
    {
        $operator = Operator::factory()->create();
        $achievement = Achievement::factory()->create(['title' => 'Judul Awal']);

        $payload = [
            'title' => 'Judul Diperbarui',
            'student_name' => $achievement->student_name,
            'category' => 'olahraga',
            'level' => 'kabupaten',
            'rank' => 'Juara 2',
            'event_name' => 'Turnamen Bola Voli',
            'event_date' => now()->format('Y-m-d'),
        ];

        $response = $this->actingAs($operator, 'operator')
            ->put(route('tu.achievements.update', $achievement), $payload);

        $response->assertRedirect(route('tu.achievements.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('achievements', [
            'id' => $achievement->id,
            'title' => 'Judul Diperbarui',
            'category' => 'olahraga',
        ]);
    }

    public function test_admin_tu_can_toggle_featured(): void
    {
        $operator = Operator::factory()->create();
        $achievement = Achievement::factory()->create(['is_featured' => false]);

        $response = $this->actingAs($operator, 'operator')
            ->post(route('tu.achievements.toggle-featured', $achievement));

        $response->assertRedirect(route('tu.achievements.index'));
        $this->assertTrue($achievement->fresh()->is_featured);

        // Toggle back
        $this->actingAs($operator, 'operator')
            ->post(route('tu.achievements.toggle-featured', $achievement));
        $this->assertFalse($achievement->fresh()->is_featured);
    }

    public function test_admin_tu_can_delete_achievement(): void
    {
        Storage::fake('public');
        $operator = Operator::factory()->create();

        $photo = UploadedFile::fake()->image('foto.jpg');
        $photoPath = $photo->store('achievements', 'public');

        $achievement = Achievement::factory()->create(['photo_path' => $photoPath]);

        $response = $this->actingAs($operator, 'operator')
            ->delete(route('tu.achievements.destroy', $achievement));

        $response->assertRedirect(route('tu.achievements.index'));
        $this->assertDatabaseMissing('achievements', ['id' => $achievement->id]);
        Storage::disk('public')->assertMissing($photoPath);
    }
}
