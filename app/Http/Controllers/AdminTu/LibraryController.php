<?php

namespace App\Http\Controllers\AdminTu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Library\BulkLoanLibraryRequest;
use App\Http\Requests\Library\StoreLibraryBookRequest;
use App\Http\Requests\Library\StoreLibraryLoanRequest;
use App\Http\Requests\Library\UpdateLibraryBookRequest;
use App\Models\Classroom;
use App\Models\LibraryBook;
use App\Models\LibraryLoan;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LibraryController extends Controller
{
    public function index(Request $request): View
    {
        $activeTab = $request->query('tab', 'loans'); // 'loans' or 'books'

        // 1. Books query
        $booksQuery = LibraryBook::query()->withCount(['activeLoans'])->latest('id');

        if ($request->filled('book_search')) {
            $search = $request->query('book_search');
            $booksQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('book_code', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $booksQuery->where('category', $request->query('category'));
        }

        if ($request->filled('class_level')) {
            $booksQuery->where('class_level', $request->query('class_level'));
        }

        $books = $booksQuery->paginate(12, ['*'], 'books_page')->withQueryString();

        // 2. Loans query
        $loansQuery = LibraryLoan::query()
            ->with(['book', 'student.classroom'])
            ->latest('loan_date')
            ->latest('id');

        if ($request->filled('loan_search')) {
            $search = $request->query('loan_search');
            $loansQuery->where(function ($q) use ($search) {
                $q->where('loan_code', 'like', "%{$search}%")
                    ->orWhereHas('student', function ($sq) use ($search) {
                        $sq->where('student_name', 'like', "%{$search}%")
                            ->orWhere('nis', 'like', "%{$search}%");
                    })
                    ->orWhereHas('book', function ($bq) use ($search) {
                        $bq->where('title', 'like', "%{$search}%")
                            ->orWhere('book_code', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            if ($request->query('status') === 'overdue') {
                $loansQuery->where('status', 'borrowed')
                    ->whereDate('due_date', '<', Carbon::today());
            } else {
                $loansQuery->where('status', $request->query('status'));
            }
        }

        if ($request->filled('classroom_id')) {
            $classId = $request->query('classroom_id');
            $loansQuery->whereHas('student', fn ($q) => $q->where('classroom_id', $classId));
        }

        $loans = $loansQuery->paginate(15, ['*'], 'loans_page')->withQueryString();

        // 3. Stats
        $stats = [
            'total_titles' => LibraryBook::count(),
            'total_stock' => (int) LibraryBook::sum('total_stock'),
            'active_loans' => LibraryLoan::where('status', 'borrowed')->count(),
            'overdue_loans' => LibraryLoan::where('status', 'borrowed')->whereDate('due_date', '<', Carbon::today())->count(),
        ];

        // 4. Dropdown options
        $students = Student::query()
            ->select(['id', 'nis', 'student_name', 'classroom_id', 'parent_phone'])
            ->with('classroom')
            ->orderBy('student_name')
            ->get();

        $classrooms = Classroom::query()->orderBy('name')->get();
        $availableBooks = LibraryBook::where('available_stock', '>', 0)->orderBy('title')->get();

        return view('tu.library.index', compact('books', 'loans', 'stats', 'students', 'classrooms', 'availableBooks', 'activeTab'));
    }

    public function storeBook(StoreLibraryBookRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('library_covers', 'public');
        }

        $data['available_stock'] = $data['total_stock'];

        LibraryBook::create($data);

        return redirect()
            ->route('tu.library.index', ['tab' => 'books'])
            ->with('success', 'Buku baru berhasil ditambahkan ke katalog perpustakaan.');
    }

    public function updateBook(UpdateLibraryBookRequest $request, LibraryBook $book): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('cover_image')) {
            if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('library_covers', 'public');
        }

        // Adjust available_stock based on total_stock change and active borrowed count
        $activeBorrowedCount = $book->activeLoans()->count();
        $newTotal = (int) $data['total_stock'];
        $data['available_stock'] = max(0, $newTotal - $activeBorrowedCount);

        $book->update($data);

        return redirect()
            ->route('tu.library.index', ['tab' => 'books'])
            ->with('success', 'Data buku berhasil diperbarui.');
    }

    public function destroyBook(LibraryBook $book): RedirectResponse
    {
        if ($book->activeLoans()->exists()) {
            return redirect()
                ->route('tu.library.index', ['tab' => 'books'])
                ->with('error', 'Buku tidak dapat dihapus karena masih ada eksemplar yang sedang dipinjam.');
        }

        if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();

        return redirect()
            ->route('tu.library.index', ['tab' => 'books'])
            ->with('success', 'Buku berhasil dihapus dari perpustakaan.');
    }

    public function storeLoan(StoreLibraryLoanRequest $request): RedirectResponse
    {
        $book = LibraryBook::findOrFail($request->book_id);

        if ($book->available_stock < 1) {
            return redirect()
                ->route('tu.library.index', ['tab' => 'loans'])
                ->with('error', 'Stok buku ini sedang habis atau seluruhnya telah dipinjam.');
        }

        DB::transaction(function () use ($request, $book) {
            $loanCode = 'PINJAM-'.date('ymd').'-'.strtoupper(Str::random(4));

            LibraryLoan::create([
                'book_id' => $book->id,
                'student_id' => $request->student_id,
                'loan_code' => $loanCode,
                'loan_date' => $request->loan_date,
                'due_date' => $request->due_date,
                'status' => 'borrowed',
                'officer_name' => $request->officer_name ?: auth()->user()?->name ?: 'Petugas TU',
                'notes' => $request->notes,
            ]);

            $book->decrement('available_stock');
        });

        return redirect()
            ->route('tu.library.index', ['tab' => 'loans'])
            ->with('success', 'Peminjaman buku berhasil dicatat.');
    }

    public function bulkLoan(BulkLoanLibraryRequest $request): RedirectResponse
    {
        $book = LibraryBook::findOrFail($request->book_id);
        $classroom = Classroom::with('students')->findOrFail($request->classroom_id);
        $students = $classroom->students;

        if ($students->isEmpty()) {
            return redirect()
                ->route('tu.library.index', ['tab' => 'loans'])
                ->with('error', "Tidak ada data siswa di kelas {$classroom->name}.");
        }

        if ($book->available_stock < $students->count()) {
            return redirect()
                ->route('tu.library.index', ['tab' => 'loans'])
                ->with('error', "Stok buku tidak mencukupi! Sisa stok tersedia: {$book->available_stock}, jumlah siswa kelas {$classroom->name}: {$students->count()}.");
        }

        DB::transaction(function () use ($request, $book, $classroom, $students) {
            foreach ($students as $student) {
                $loanCode = 'BOS-'.date('ymd').'-'.strtoupper(Str::random(4));

                LibraryLoan::create([
                    'book_id' => $book->id,
                    'student_id' => $student->id,
                    'loan_code' => $loanCode,
                    'loan_date' => $request->loan_date,
                    'due_date' => $request->due_date,
                    'status' => 'borrowed',
                    'officer_name' => $request->officer_name ?: auth()->user()?->name ?: 'Petugas Perpustakaan/TU',
                    'notes' => ($request->notes ? $request->notes.' - ' : '')."Peminjaman Massal Kelas {$classroom->name}",
                ]);
            }

            $book->decrement('available_stock', $students->count());
        });

        return redirect()
            ->route('tu.library.index', ['tab' => 'loans'])
            ->with('success', "Berhasil mendistribusikan buku '{$book->title}' kepada {$students->count()} siswa kelas {$classroom->name}.");
    }

    public function returnLoan(Request $request, LibraryLoan $loan): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:returned,lost,damaged'],
            'fine_amount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        if ($loan->status !== 'borrowed') {
            return redirect()
                ->route('tu.library.index', ['tab' => 'loans'])
                ->with('error', 'Status peminjaman ini sudah diselesaikan sebelumnya.');
        }

        DB::transaction(function () use ($request, $loan) {
            $status = $request->status;

            $loan->update([
                'status' => $status,
                'return_date' => Carbon::today(),
                'fine_amount' => $request->filled('fine_amount') ? $request->fine_amount : 0,
                'notes' => $request->filled('notes') ? $request->notes : $loan->notes,
            ]);

            // If returned, increment available stock back
            if ($status === 'returned') {
                $loan->book->increment('available_stock');
            }
        });

        return redirect()
            ->route('tu.library.index', ['tab' => 'loans'])
            ->with('success', 'Pengembalian buku berhasil diproses.');
    }

    public function destroyLoan(LibraryLoan $loan): RedirectResponse
    {
        DB::transaction(function () use ($loan) {
            // If deleting a loan that is still borrowed, restore stock
            if ($loan->status === 'borrowed') {
                $loan->book->increment('available_stock');
            }
            $loan->delete();
        });

        return redirect()
            ->route('tu.library.index', ['tab' => 'loans'])
            ->with('success', 'Riwayat peminjaman berhasil dihapus.');
    }
}
