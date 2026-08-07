<?php

namespace Tests\Unit\Public\Actions;

use App\Actions\Public\StoreContactMessageAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreContactMessageActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_stores_message()
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'Pertanyaan',
            'message' => 'Halo, saya mau tanya.',
        ];

        $action = new StoreContactMessageAction;
        $message = $action->execute($data);

        $this->assertDatabaseHas('messages', [
            'id' => $message->id,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'Pertanyaan',
            'message' => 'Halo, saya mau tanya.',
        ]);
    }
}
