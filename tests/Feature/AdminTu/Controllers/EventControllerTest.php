<?php

namespace Tests\Feature\AdminTu\Controllers;

use App\Models\Event;
use App\Models\Operator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_displays_events_list(): void
    {
        $operator = Operator::factory()->create(['role' => 'SMP']);
        Event::factory()->count(3)->create();

        $response = $this->actingAs($operator, 'operator')
            ->get(route('tu.events.index'));

        $response->assertStatus(200);
        $response->assertViewIs('tu.events.index');
        $response->assertViewHas('events');
    }

    public function test_it_displays_event_detail_show(): void
    {
        $operator = Operator::factory()->create(['role' => 'SMP']);
        $event = Event::factory()->create([
            'title' => 'Ujian Akhir Semester',
            'type' => 'academic',
        ]);

        $response = $this->actingAs($operator, 'operator')
            ->get(route('tu.events.show', $event->id));

        $response->assertStatus(200);
        $response->assertViewIs('tu.events.show');
        $response->assertViewHas('event');
        $response->assertSee('Ujian Akhir Semester');
    }

    public function test_it_returns_json_when_requested_on_show(): void
    {
        $operator = Operator::factory()->create(['role' => 'SMP']);
        $event = Event::factory()->create([
            'title' => 'Rapat Dewan Guru',
        ]);

        $response = $this->actingAs($operator, 'operator')
            ->getJson(route('tu.events.show', $event->id));

        $response->assertStatus(200);
        $response->assertJsonPath('title', 'Rapat Dewan Guru');
    }

    public function test_it_displays_create_event_page(): void
    {
        $operator = Operator::factory()->create(['role' => 'SMP']);

        $response = $this->actingAs($operator, 'operator')
            ->get(route('tu.events.create'));

        $response->assertStatus(200);
        $response->assertViewIs('tu.events.create');
    }

    public function test_it_stores_new_event(): void
    {
        $operator = Operator::factory()->create(['role' => 'SMP']);

        $response = $this->actingAs($operator, 'operator')
            ->post(route('tu.events.store'), [
                'title' => 'Lomba Olahraga Sekolah',
                'start_date' => '2026-11-10',
                'end_date' => '2026-11-12',
                'type' => 'event',
                'description' => 'Lomba futsal dan bola voli antar kelas',
            ]);

        $response->assertRedirect(route('tu.events.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('events', [
            'title' => 'Lomba Olahraga Sekolah',
            'type' => 'event',
        ]);
    }

    public function test_it_displays_edit_event_page(): void
    {
        $operator = Operator::factory()->create(['role' => 'SMP']);
        $event = Event::factory()->create([
            'title' => 'Upacara Hari Guru',
        ]);

        $response = $this->actingAs($operator, 'operator')
            ->get(route('tu.events.edit', $event->id));

        $response->assertStatus(200);
        $response->assertViewIs('tu.events.edit');
        $response->assertViewHas('event');
        $response->assertSee('Upacara Hari Guru');
    }

    public function test_it_updates_event(): void
    {
        $operator = Operator::factory()->create(['role' => 'SMP']);
        $event = Event::factory()->create([
            'title' => 'Jadwal Lama',
            'type' => 'academic',
        ]);

        $response = $this->actingAs($operator, 'operator')
            ->put(route('tu.events.update', $event->id), [
                'title' => 'Jadwal Baru Terverifikasi',
                'start_date' => '2026-12-01',
                'end_date' => '2026-12-05',
                'type' => 'academic',
                'description' => 'Deskripsi diperbarui',
            ]);

        $response->assertRedirect(route('tu.events.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('events', [
            'id' => $event->id,
            'title' => 'Jadwal Baru Terverifikasi',
            'type' => 'academic',
        ]);
    }

    public function test_it_deletes_event(): void
    {
        $operator = Operator::factory()->create(['role' => 'SMP']);
        $event = Event::factory()->create();

        $response = $this->actingAs($operator, 'operator')
            ->delete(route('tu.events.destroy', $event->id));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('events', ['id' => $event->id]);
    }

    public function test_guest_cannot_access_events(): void
    {
        $event = Event::factory()->create();

        $this->get(route('tu.events.index'))->assertRedirect(route('login'));
        $this->get(route('tu.events.show', $event->id))->assertRedirect(route('login'));
        $this->post(route('tu.events.store'), [])->assertRedirect(route('login'));
    }
}
