<?php

namespace App\Http\Controllers\AdminTu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::latest()->paginate(10);
        return view('tu.events.index', compact('events'));
    }

    public function create()
    {
        return view('tu.events.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'type' => 'required',
        ]);

        Event::create($request->all());

        return redirect()->route('tu.events.index')->with('success', 'Agenda berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        Event::findOrFail($id)->delete();
        return back()->with('success', 'Agenda berhasil dihapus.');
    }
}