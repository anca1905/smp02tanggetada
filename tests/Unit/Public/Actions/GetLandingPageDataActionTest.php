<?php

namespace Tests\Unit\Public\Actions;

use App\Actions\Public\GetLandingPageDataAction;
use App\Models\Post;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetLandingPageDataActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_latest_posts_and_counts()
    {
        // Add setup lines if Database has remaining data or clear previous states.
        Post::query()->delete();
        Teacher::query()->delete();
        Student::query()->delete();

        Post::factory()->count(5)->create(['is_published' => true]);
        Post::factory()->count(2)->create(['is_published' => false]);

        Teacher::factory()->count(3)->create(['status' => 'Active']);
        Teacher::factory()->count(1)->create(['status' => 'Inactive']);

        Student::factory()->count(10)->create(['student_status' => 'Active']);
        Student::factory()->count(2)->create(['student_status' => 'Inactive']);

        $action = new GetLandingPageDataAction;
        $data = $action->execute();

        $this->assertArrayHasKey('latest_posts', $data);
        $this->assertArrayHasKey('staff', $data);
        $this->assertArrayHasKey('student', $data);

        $this->assertCount(3, $data['latest_posts']);
        $this->assertEquals(Teacher::where('status', 'Active')->count(), $data['staff']);
        $this->assertEquals(Student::where('student_status', 'Active')->count(), $data['student']);
    }
}
