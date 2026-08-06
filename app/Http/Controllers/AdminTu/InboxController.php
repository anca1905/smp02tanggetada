<?php

namespace App\Http\Controllers\AdminTu;

use App\Actions\Inbox\DeleteMessageAction;
use App\Actions\Inbox\GetMessagesAction;
use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\RedirectResponse;

class InboxController extends Controller
{
    /**
     * Menampilkan daftar pesan masuk.
     */
    public function index(GetMessagesAction $action): View
    {
        $messages = $action->execute();

        return view('tu.inbox.index', compact('messages'));
    }

    /**
     * Menghapus pesan masuk.
     */
    public function destroy(
        Message $message,
        DeleteMessageAction $action
    ): RedirectResponse {
        $action->execute($message);

        return back()->with('success', 'Pesan berhasil dihapus.');
    }
}
