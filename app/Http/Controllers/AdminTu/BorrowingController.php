<?php

namespace App\Http\Controllers\AdminTu;

use App\Actions\Borrowing\CreateBorrowingAction;
use App\Actions\Borrowing\DeleteBorrowingAction;
use App\Actions\Borrowing\GetBorrowingIndexDataAction;
use App\Actions\Borrowing\UpdateBorrowingAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Borrowing\StoreBorrowingRequest;
use App\Http\Requests\Borrowing\UpdateBorrowingRequest;
use App\Models\RoomBorrowing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BorrowingController extends Controller
{
    /**
     * Menampilkan halaman riwayat peminjaman ruangan
     */
    public function index(
        Request $request,
        GetBorrowingIndexDataAction $action,
    ): View {
        $data = $action->execute(
            $request->input('search'),
            $request->input('status'),
        );

        return view('tu.borrowing_a_room', $data);
    }

    /**
     * Menerima dan menyimpan pengajuan peminjaman baru
     */
    public function store(
        StoreBorrowingRequest $request,
        CreateBorrowingAction $action,
    ): RedirectResponse {
        $action->execute($request->validated());

        return back()->with('success', 'Peminjaman berhasil diajukan!');
    }

    /**
     * Memutakhirkan informasi atau status peminjaman
     */
    public function update(
        UpdateBorrowingRequest $request,
        RoomBorrowing $borrowing,
        UpdateBorrowingAction $action,
    ): RedirectResponse {
        $action->execute($request->validated(), $borrowing);

        return back()->with('success', 'Data peminjaman diperbarui!');
    }

    /**
     * Menghapus catatan peminjaman ruangan dari basis data
     */
    public function destroy(
        RoomBorrowing $borrowing,
        DeleteBorrowingAction $action,
    ): RedirectResponse {
        $action->execute($borrowing);

        return back()->with('success', 'Data peminjaman dihapus!');
    }
}
