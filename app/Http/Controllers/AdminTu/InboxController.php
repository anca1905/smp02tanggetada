<?php

namespace App\Http\Controllers\AdminTu;

use App\Http\Controllers\Controller;
use App\Models\Message;

class InboxController extends Controller
{
    public function index()
    {
        $messages = Message::latest()->paginate(10);
        return view('tu.inbox.index', compact('messages'));
    }

    public function destroy($id)
    {
        Message::findOrFail($id)->delete();
        return back()->with('success', 'Pesan berhasil dihapus.');
    }
}