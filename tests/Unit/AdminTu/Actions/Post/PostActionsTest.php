<?php

namespace Tests\Unit\AdminTu\Actions\Post;

use App\Actions\Post\DeletePostAction;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostActionsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_it_deletes_post_and_removes_thumbnail()
    {
        $filePath = 'posts/dummy.jpg';
        Storage::disk('public')->put($filePath, 'dummy content');

        $post = Post::factory()->create([
            'image' => $filePath,
        ]);

        Storage::disk('public')->assertExists($filePath);

        $action = new DeletePostAction;
        $action->execute($post);

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
        Storage::disk('public')->assertMissing($filePath);
    }
}
