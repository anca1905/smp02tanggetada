<?php

namespace App\Actions\Public;

use App\Models\Post;
use App\Models\Student;
use App\Models\Teacher;

class GetLandingPageDataAction
{
    /**
     * Get data for landing page: latest posts, active staff count, active student count.
     */
    public function execute(): array
    {
        $latest_posts = Post::where('is_published', true)->latest()->take(3)->get();
        $staff = Teacher::where('status', 'Active')->count();
        $student = Student::where('student_status', 'Active')->count();

        return compact('latest_posts', 'staff', 'student');
    }
}
