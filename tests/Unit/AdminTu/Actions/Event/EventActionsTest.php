<?php

namespace Tests\Unit\AdminTu\Actions\Event;

use App\Actions\Event\CreateEventAction;
use App\Actions\Event\DeleteEventAction;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventActionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_event()
    {
        $action = new CreateEventAction;
        $event = $action->execute([
            'title' => 'Rapat Wali Murid',
            'description' => 'Pembagian Raport',
            'start_date' => '2026-12-15',
            'end_date' => '2026-12-15',
            'type' => 'academic',
        ]);

        $this->assertEquals('Rapat Wali Murid', $event->title);
        $this->assertDatabaseHas('events', ['title' => 'Rapat Wali Murid']);
    }

    public function test_it_deletes_event()
    {
        $event = Event::factory()->create();

        $action = new DeleteEventAction;
        $action->execute($event);

        $this->assertDatabaseMissing('events', ['id' => $event->id]);
    }
}
