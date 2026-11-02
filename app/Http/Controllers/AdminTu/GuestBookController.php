<?php

namespace App\Http\Controllers\AdminTu;

use App\Http\Controllers\Controller;
use App\Models\GuestBook;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class GuestBookController extends Controller
{
    public function index(Request $request): View
    {
        $query = GuestBook::query()->latest('visit_date')->latest('id');

        if ($request->filled('category')) {
            $query->where('category', $request->query('category'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        if ($request->query('date_filter') === 'today') {
            $query->whereDate('visit_date', today());
        } elseif ($request->query('date_filter') === 'this_month') {
            $query->whereMonth('visit_date', now()->month)->whereYear('visit_date', now()->year);
        } elseif ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('visit_date', [$request->query('start_date'), $request->query('end_date')]);
        }

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('institution', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('purpose', 'like', "%{$search}%");
            });
        }

        $guests = $query->paginate(15)->withQueryString();

        $stats = [
            'today' => GuestBook::whereDate('visit_date', today())->count(),
            'this_month' => GuestBook::whereMonth('visit_date', now()->month)->whereYear('visit_date', now()->year)->count(),
            'waiting' => GuestBook::where('status', 'waiting')->count(),
            'total' => GuestBook::count(),
        ];

        return view('tu.guest_books.index', compact('guests', 'stats'));
    }

    public function updateStatus(Request $request, GuestBook $guestBook): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:waiting,in_progress,completed'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $guestBook->update($validated);

        return redirect()
            ->route('tu.guest-books.index')
            ->with('success', 'Status kunjungan tamu berhasil diperbarui.');
    }

    public function destroy(GuestBook $guestBook): RedirectResponse
    {
        $guestBook->delete();

        return redirect()
            ->route('tu.guest-books.index')
            ->with('success', 'Catatan buku tamu berhasil dihapus.');
    }

    public function exportPdf(Request $request): Response
    {
        $query = GuestBook::query()->latest('visit_date')->latest('id');

        if ($request->filled('category')) {
            $query->where('category', $request->query('category'));
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('visit_date', [$request->query('start_date'), $request->query('end_date')]);
        }

        $guests = $query->get();
        $site_settings = Setting::pluck('value', 'key')->toArray();

        $pdf = Pdf::loadView('pdf.guest_book_recap', compact('guests', 'site_settings'))
            ->setPaper('a4', 'landscape');

        $filename = 'buku-tamu-'.now()->format('Y-m-d').'.pdf';

        return $pdf->download($filename);
    }
}
