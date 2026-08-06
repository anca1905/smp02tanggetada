<?php

namespace App\Http\Controllers\AdminTu;

use App\Actions\Event\CreateEventAction;
use App\Actions\Event\DeleteEventAction;
use App\Actions\Event\GetEventsAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Event\StoreEventRequest;
use App\Models\Event;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\RedirectResponse;

class EventController extends Controller
{
    /**
     * Menampilkan daftar agenda.
     */
    public function index(GetEventsAction $action): View
    {
        $events = $action->execute();

        return view('tu.events.index', compact('events'));
    }

    /**
     * Menampilkan form tambah agenda.
     */
    public function create(): View
    {
        return view('tu.events.create');
    }

    /**
     * Menambahkan agenda baru.
     */
    public function store(
        StoreEventRequest $request,
        CreateEventAction $action
    ): RedirectResponse {
        $action->execute($request->validated());

        return redirect()->route('tu.events.index')->with('success', 'Agenda berhasil ditambahkan!');
    }

    /**
     * Menghapus agenda.
     */
    public function destroy(
        Event $event,
        DeleteEventAction $action
    ): RedirectResponse {
        $action->execute($event);

        return back()->with('success', 'Agenda berhasil dihapus.');
    }
}
