<?php

namespace Tests\Unit\AdminTu\Actions\Borrowing;

use App\Actions\Borrowing\UpdateBorrowingAction;
use App\Models\RoomBorrowing;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProcessRoomBorrowingActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_approves_borrowing()
    {
        $borrowing = RoomBorrowing::factory()->create(['status' => 'upcoming']);

        $action = new UpdateBorrowingAction;

        $action->execute([
            'status' => 'ongoing',
        ], $borrowing);

        $this->assertEquals('ongoing', $borrowing->fresh()->status);
    }
}
