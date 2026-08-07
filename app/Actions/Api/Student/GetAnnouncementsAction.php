<?php

namespace App\Actions\Api\Student;

class GetAnnouncementsAction
{
    /**
     * Get latest 20 announcements.
     */
    public function execute(): array
    {
        $announcements = \App\Models\Post::where('is_published', true)
            ->latest()
            ->take(20)
            ->get(['id', 'title', 'content', 'category', 'created_at']);

        return [
            'success' => true,
            'data' => $announcements,
        ];
    }
}
