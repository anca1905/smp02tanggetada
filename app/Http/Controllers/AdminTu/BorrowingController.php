<?php

namespace App\Http\Controllers\AdminTu;

use App\Http\Controllers\Controller;
use App\Models\Borrowing_a_room;
use App\Models\Room;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BorrowingController extends Controller
{
    public function index(Request $request)
    {
        $ruanganList = Room::all();
        
        $query = Borrowing_a_room::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('activity_description', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $bookings = $query->orderBy('borrow_date', 'desc')
            ->orderBy('start_time', 'asc')
            ->paginate(10);

        return view('tu.borrowing_a_room', compact('bookings', 'ruanganList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name'            => 'required|string|max:100',
            'nis'                  => 'nullable|string|max:20',
            'class'                => 'nullable|string|max:10',
            'phone_number'         => 'nullable|string|max:20',
            'room_type'            => 'required|string',
            'borrow_date'          => 'required|date',
            'start_time'           => 'required',
            'end_time'             => 'required|after:start_time',
            'activity_description' => 'required|string',
            'responsible_person'   => 'required|string|max:100',
        ]);

        $status = 'upcoming';
        $borrowDate = Carbon::parse($request->borrow_date);

        if ($borrowDate->isBefore(now()->startOfDay())) {
            $status = 'completed';
        }

        Borrowing_a_room::create(array_merge($request->all(), ['status' => $status]));

        return back()->with('success', 'Peminjaman berhasil diajukan!');
    }

    public function update(Request $request, $id)
    {
        $booking = Borrowing_a_room::findOrFail($id);

        $request->validate([
            'full_name'            => 'required|string',
            'room_type'            => 'required|string',
            'borrow_date'          => 'required|date',
            'start_time'           => 'required',
            'end_time'             => 'required|after:start_time',
            'status'               => 'required|in:upcoming,ongoing,completed',
        ]);

        $booking->update($request->all());

        return back()->with('success', 'Data peminjaman diperbarui!');
    }

    public function destroy($id)
    {
        $booking = Borrowing_a_room::findOrFail($id);
        $booking->delete();

        return back()->with('success', 'Data peminjaman dihapus!');
    }
}