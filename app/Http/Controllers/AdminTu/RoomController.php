<?php

namespace App\Http\Controllers\AdminTu;

use App\Actions\Room\CreateRoomAction;
use App\Actions\Room\DeleteRoomAction;
use App\Actions\Room\GetRoomsAction;
use App\Actions\Room\UpdateRoomAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Room\StoreRoomRequest;
use App\Http\Requests\Room\UpdateRoomRequest;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request, GetRoomsAction $action)
    {
        $rooms = $action->execute($request->search, 10);
        return view("tu.room_data", compact("rooms"));
    }

    public function store(StoreRoomRequest $request, CreateRoomAction $action)
    {
        $action->execute($request->validated());
        return back()->with("success", "Room berhasil ditambahkan!");
    }

    public function update(
        UpdateRoomRequest $request,
        Room $room,
        UpdateRoomAction $action,
    ) {
        $action->execute($request->validated(), $room);
        return back()->with("success", "Data Room berhasil diperbarui!");
    }

    public function destroy(Room $room, DeleteRoomAction $action)
    {
        $action->execute($room);
        return back()->with("success", "Room berhasil dihapus!");
    }
}
