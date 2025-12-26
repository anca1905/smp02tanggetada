<?php

namespace App\Http\Controllers\AdminTu;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        
        $query = Room::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('room_name', 'like', "%{$search}%")
                ->orWhere('location', 'like', "%{$search}%");
        }

        $rooms = $query->orderBy('room_name', 'asc')->paginate(10);

        return view('tu.room_data', compact('rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_name' => 'required|string|max:100',
            'location'       => 'required|string|max:100',
            'description'   => 'nullable|string',
        ]);

        Room::create($request->all());

        return back()->with('success', 'Room berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);

        $request->validate([
            'room_name' => 'required|string|max:100',
            'location'       => 'required|string|max:100',
            'description'   => 'nullable|string',
        ]);

        $room->update($request->all());

        return back()->with('success', 'Data Room berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        $room->delete();

        return back()->with('success', 'Room berhasil dihapus!');
    }
}
