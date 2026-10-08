<?php

namespace App\Http\Controllers\AdminTu;

use App\Actions\Event\CreateEventAction;
use App\Actions\Event\DeleteEventAction;
use App\Actions\Event\GetEventsAction;
use App\Actions\Event\UpdateEventAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Event\StoreEventRequest;
use App\Http\Requests\Event\UpdateEventRequest;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
     * Menampilkan detail agenda.
     */
    public function show(Request $request, Event $event): View|JsonResponse
    {
        if ($request->wantsJson()) {
            return response()->json($event);
        }

        return view('tu.events.show', compact('event'));
    }

    /**
     * Menampilkan form edit agenda.
     */
    public function edit(Event $event): View
    {
        return view('tu.events.edit', compact('event'));
    }

    /**
     * Memperbarui data agenda.
     */
    public function update(
        UpdateEventRequest $request,
        Event $event,
        UpdateEventAction $action
    ): RedirectResponse {
        $action->execute($event, $request->validated());

        return redirect()->route('tu.events.index')->with('success', 'Agenda berhasil diperbarui!');
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
