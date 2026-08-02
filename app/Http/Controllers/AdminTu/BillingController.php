<?php

namespace App\Http\Controllers\AdminTu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\Student;
use Carbon\Carbon;

class BillingController extends Controller
{
    public function index()
    {
        $bills = Bill::with('student')->latest()->get();
        return view('tu.billing.index', compact('bills'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'month' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
        ]);

        $students = Student::all();
        $title = "SPP " . $request->month;

        foreach ($students as $student) {
            Bill::create([
                'student_id' => $student->id,
                'title' => $title,
                'type' => 'SPP Bulanan',
                'amount' => $request->amount,
                'due_date' => $request->due_date,
                'status' => 'unpaid',
            ]);
        }

        return redirect()->route('tu.billing.index')->with('success', 'Berhasil membuat tagihan ' . $title . ' untuk seluruh siswa.');
    }

    public function markAsPaid($id)
    {
        $bill = Bill::findOrFail($id);
        $bill->update([
            'status' => 'paid',
            'paid_at' => Carbon::now(),
        ]);

        return redirect()->route('tu.billing.index')->with('success', 'Tagihan ' . $bill->title . ' atas nama ' . $bill->student->student_name . ' berhasil ditandai Lunas.');
    }

    public function destroy($id)
    {
        Bill::findOrFail($id)->delete();
        return redirect()->route('tu.billing.index')->with('success', 'Tagihan berhasil dihapus.');
    }
}
