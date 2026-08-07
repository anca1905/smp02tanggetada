<?php

namespace App\Actions\Public;

use App\Models\Message;

class StoreContactMessageAction
{
    /**
     * Store new contact message.
     */
    public function execute(array $data): Message
    {
        return Message::create($data);
    }
}
