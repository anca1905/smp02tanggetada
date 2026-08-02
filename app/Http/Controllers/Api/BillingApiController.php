<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bill;

class BillingApiController extends Controller
{
    public function getStudentBills(Request $request)
    {
        $student = $request->user(); // From sanctum
        
        // If not using Sanctum for API currently, and passing student_id via request:
        $studentId = $request->input('student_id');

        if (!$studentId && $student) {
            $studentId = $student->id;
        }

        if (!$studentId) {
            return response()->json(['success' => false, 'message' => 'Student ID required'], 400);
        }

        $bills = Bill::where('student_id', $studentId)
                    ->orderBy('due_date', 'desc')
                    ->get();

        $activeBills = $bills->where('status', 'unpaid')->values();
        $historyBills = $bills->where('status', 'paid')->values();

        $totalTerbayar = $historyBills->sum('amount');
        $sisaTagihan = $activeBills->sum('amount');

        return response()->json([
            'success' => true,
            'data' => [
                'summary' => [
                    'total_tagihan' => 'Rp' . number_format($sisaTagihan, 0, ',', '.'),
                    'total_terbayar' => 'Rp' . number_format($totalTerbayar, 0, ',', '.'),
                    'sisa_tagihan' => 'Rp' . number_format($sisaTagihan, 0, ',', '.'),
                    'tagihan_belum_dibayar' => $activeBills->count(),
                    'transaksi_berhasil' => $historyBills->count(),
                    'status' => $activeBills->isEmpty() ? 'Lancar' : 'Ada Tunggakan'
                ],
                'active_bills' => $activeBills->map(function ($bill) {
                    return [
                        'id' => $bill->id,
                        'title' => $bill->title,
                        'type' => $bill->type,
                        'amount' => 'Rp' . number_format($bill->amount, 0, ',', '.'),
                        'raw_amount' => $bill->amount,
                        'due_date' => $bill->due_date->format('d M Y'),
                        'status' => 'Belum Dibayar',
                    ];
                }),
                'history_bills' => $historyBills->map(function ($bill) {
                    return [
                        'id' => $bill->id,
                        'title' => $bill->title,
                        'type' => $bill->type,
                        'amount' => 'Rp' . number_format($bill->amount, 0, ',', '.'),
                        'paid_at' => $bill->paid_at ? $bill->paid_at->format('d M Y, H:i') : '-',
                    ];
                })
            ]
        ]);
    }
}
