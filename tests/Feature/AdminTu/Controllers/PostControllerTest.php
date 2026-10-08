<?php

namespace Tests\Feature\AdminTu\Controllers;

use App\Models\Operator;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_it_displays_posts_list(): void
    {
        $operator = Operator::factory()->create(['role' => 'SMP']);
        Post::factory()->count(3)->create();

        $response = $this->actingAs($operator, 'operator')
            ->get(route('tu.posts.index'));

        $response->assertStatus(200);
        $response->assertViewIs('tu.posts.index');
        $response->assertViewHas('posts');
    }

    public function test_it_displays_create_post_page(): void
    {
        $operator = Operator::factory()->create(['role' => 'SMP']);

        $response = $this->actingAs($operator, 'operator')
            ->get(route('tu.posts.create'));

        $response->assertStatus(200);
        $response->assertViewIs('tu.posts.create');
    }

    public function test_it_stores_new_post_with_thumbnail(): void
    {
        $operator = Operator::factory()->create(['role' => 'SMP']);
        $file = UploadedFile::fake()->image('thumbnail.jpg', 600, 400);

        $response = $this->actingAs($operator, 'operator')
            ->post(route('tu.posts.store'), [
                'title' => 'Prestasi Baru Siswa SMPN 2 Tanggetada',
                'category' => 'Prestasi',
                'content' => '<p>Artikel lengkap dengan <strong>prestasi gemilang</strong>.</p>',
                'image' => $file,
            ]);

        $response->assertRedirect(route('tu.posts.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('posts', [
            'title' => 'Prestasi Baru Siswa SMPN 2 Tanggetada',
            'category' => 'Prestasi',
            'slug' => 'prestasi-baru-siswa-smpn-2-tanggetada',
        ]);

        $post = Post::where('title', 'Prestasi Baru Siswa SMPN 2 Tanggetada')->first();
        $this->assertNotNull($post->image);
        Storage::disk('public')->assertExists($post->image);
    }

    public function test_it_displays_edit_post_page(): void
    {
        $operator = Operator::factory()->create(['role' => 'SMP']);
        $post = Post::factory()->create([
            'title' => 'Judul Artikel Lama',
            'content' => '<p>Konten lama</p>',
        ]);

        $response = $this->actingAs($operator, 'operator')
            ->get(route('tu.posts.edit', $post->id));

        $response->assertStatus(200);
        $response->assertViewIs('tu.posts.edit');
        $response->assertViewHas('post');
        $response->assertSee('Judul Artikel Lama');
    }

    public function test_it_updates_post_and_replaces_thumbnail(): void
    {
        $operator = Operator::factory()->create(['role' => 'SMP']);
        $oldFile = UploadedFile::fake()->image('old_thumb.jpg');
        $oldPath = $oldFile->store('posts', 'public');

        $post = Post::factory()->create([
            'title' => 'Judul Sebelum Update',
            'category' => 'Pengumuman',
            'content' => '<p>Konten sebelum update</p>',
            'image' => $oldPath,
        ]);

        $newFile = UploadedFile::fake()->image('new_thumb.jpg');

        $response = $this->actingAs($operator, 'operator')
            ->put(route('tu.posts.update', $post->id), [
                'title' => 'Judul Setelah Update',
                'category' => 'Kegiatan',
                'content' => '<p>Konten baru setelah diperbarui oleh Summernote.</p>',
                'image' => $newFile,
            ]);

        $response->assertRedirect(route('tu.posts.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Judul Setelah Update',
            'category' => 'Kegiatan',
            'slug' => 'judul-setelah-update',
        ]);

        $post->refresh();
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($post->image);
    }

    public function test_it_uploads_inline_image_via_ajax(): void
    {
        $operator = Operator::factory()->create(['role' => 'SMP']);
        $inlineImage = UploadedFile::fake()->image('content_photo.png', 800, 600);

        $response = $this->actingAs($operator, 'operator')
            ->postJson(route('tu.posts.upload-image'), [
                'image' => $inlineImage,
            ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['url']);

        $url = $response->json('url');
        $this->assertNotEmpty($url);

        // Verify that image exists on public storage
        $filename = basename($url);
        Storage::disk('public')->assertExists('posts/content/'.$filename);
    }

    public function test_it_deletes_post(): void
    {
        $operator = Operator::factory()->create(['role' => 'SMP']);
        $post = Post::factory()->create();

        $response = $this->actingAs($operator, 'operator')
            ->delete(route('tu.posts.destroy', $post->id));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_guest_cannot_access_posts_management(): void
    {
        $post = Post::factory()->create();

        $this->get(route('tu.posts.index'))->assertRedirect(route('login'));
        $this->get(route('tu.posts.create'))->assertRedirect(route('login'));
        $this->get(route('tu.posts.edit', $post->id))->assertRedirect(route('login'));
        $this->post(route('tu.posts.store'), [])->assertRedirect(route('login'));
        $this->postJson(route('tu.posts.upload-image'), [])->assertStatus(401);
    }
}
