<?php

namespace App\Http\Controllers\AdminTu;

use App\Actions\Bill\DeleteBillAction;
use App\Actions\Bill\GenerateBillsAction;
use App\Actions\Bill\GetBillsAction;
use App\Actions\Bill\MarkBillAsPaidAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Bill\GenerateBillingRequest;
use App\Models\Bill;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BillingController extends Controller
{
    /**
     * Menampilkan daftar seluruh tagihan siswa
     */
    public function index(GetBillsAction $action): View
    {
        $bills = $action->execute();

        return view('tu.billing.index', compact('bills'));
    }

    /**
     * Membuat tagihan SPP bulanan baru secara massal ke seluruh siswa.
     */
    public function generate(
        GenerateBillingRequest $request,
        GenerateBillsAction $action,
    ): RedirectResponse {
        $title = $action->execute($request->validated());

        return redirect()
            ->route('tu.billing.index')
            ->with(
                'success',
                'Berhasil membuat tagihan '.$title.' untuk seluruh siswa.',
            );
    }

    /**
     * Menandai bahwa tagihan sudah Lunas
     */
    public function markAsPaid(
        Bill $bill,
        MarkBillAsPaidAction $action,
    ): RedirectResponse {
        $paidBill = $action->execute($bill);

        return redirect()
            ->route('tu.billing.index')
            ->with(
                'success',
                'Tagihan '.
                    $paidBill->title.
                    ' atas nama '.
                    ($paidBill->student->student_name ?? 'Siswa').
                    ' berhasil ditandai Lunas.',
            );
    }

    /**
     * Menghapus sebuah tagihan dari basis data
     */
    public function destroy(
        Bill $bill,
        DeleteBillAction $action,
    ): RedirectResponse {
        $action->execute($bill);

        return redirect()
            ->route('tu.billing.index')
            ->with('success', 'Tagihan berhasil dihapus.');
    }
}
