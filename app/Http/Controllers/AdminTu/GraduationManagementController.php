<?php

namespace App\Http\Controllers\AdminTu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Graduation\StoreGraduationRequest;
use App\Http\Requests\Graduation\UpdateGraduationRequest;
use App\Models\Graduation;
use App\Models\Setting;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class GraduationManagementController extends Controller
{
    public function index(Request $request): View
    {
        $query = Graduation::query()->with('student.classroom')->latest('id');

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('student_name', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%")
                    ->orWhere('letter_number', 'like', "%{$search}%");
            });
        }

        $graduations = $query->paginate(15)->withQueryString();

        // Students available for manual addition
        $existingStudentIds = Graduation::pluck('student_id')->toArray();
        $availableStudents = Student::query()
            ->whereNotIn('id', $existingStudentIds)
            ->with('classroom')
            ->orderBy('student_name')
            ->get();

        // Count class 9 students not yet in graduation list
        $classNineUnimportedCount = Student::query()
            ->whereNotIn('id', $existingStudentIds)
            ->whereHas('classroom', function ($q) {
                $q->where('name', 'like', '9%')
                    ->orWhere('name', 'like', 'IX%');
            })
            ->count();

        // Settings
        $announcementStatus = Setting::where('key', 'graduation_status')->value('value') ?? '1';
        $announcementDate = Setting::where('key', 'graduation_announcement_date')->value('value') ?? now()->format('Y-m-d\TH:i');
        $letterPrefix = Setting::where('key', 'graduation_letter_number_prefix')->value('value') ?? '421.3/SKL/{NO}/SMP-02/'.date('Y');
        $headmasterName = Setting::where('key', 'graduation_headmaster_name')->value('value') ?? 'Drs. H. Mulyadi, M.Pd.';
        $headmasterNip = Setting::where('key', 'graduation_headmaster_nip')->value('value') ?? '19720315 199802 1 004';

        $stats = [
            'total' => Graduation::count(),
            'lulus' => Graduation::where('status', 'lulus')->count(),
            'ditunda' => Graduation::where('status', 'ditunda')->count(),
            'tidak_lulus' => Graduation::where('status', 'tidak_lulus')->count(),
            'average' => round((float) Graduation::avg('average_score'), 2),
        ];

        return view('tu.graduations.index', compact(
            'graduations',
            'availableStudents',
            'classNineUnimportedCount',
            'announcementStatus',
            'announcementDate',
            'letterPrefix',
            'headmasterName',
            'headmasterNip',
            'stats'
        ));
    }

    public function store(StoreGraduationRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $student = Student::with('classroom')->findOrFail($data['student_id']);

        $data['nis'] = $student->nis;
        $data['nisn'] = $data['nisn'] ?? $student->nisn;
        $data['student_name'] = $student->student_name;
        $data['classroom_name'] = $student->classroom->name ?? '-';
        $data['graduation_date'] = $data['graduation_date'] ?? now()->format('Y-m-d');

        // Auto generate letter number if empty
        if (empty($data['letter_number'])) {
            $prefix = Setting::where('key', 'graduation_letter_number_prefix')->value('value') ?? '421.3/SKL/{NO}/SMP-02/'.date('Y');
            $nextIndex = str_pad(Graduation::count() + 1, 3, '0', STR_PAD_LEFT);
            $data['letter_number'] = str_replace('{NO}', $nextIndex, $prefix);
        }

        $graduation = Graduation::create($data);

        if ($data['status'] === 'lulus') {
            $student->update(['student_status' => 'Graduated']);
        }

        return redirect()
            ->route('tu.graduations.index')
            ->with('success', "Data kelulusan untuk {$student->student_name} berhasil ditambahkan.");
    }

    public function update(UpdateGraduationRequest $request, Graduation $graduation): RedirectResponse
    {
        $data = $request->validated();
        $graduation->update($data);

        if ($graduation->student) {
            $newStatus = ($data['status'] === 'lulus') ? 'Graduated' : 'Active';
            $graduation->student->update(['student_status' => $newStatus]);
        }

        return redirect()
            ->route('tu.graduations.index')
            ->with('success', 'Data kelulusan berhasil diperbarui.');
    }

    public function destroy(Graduation $graduation): RedirectResponse
    {
        if ($graduation->student) {
            $graduation->student->update(['student_status' => 'Active']);
        }

        $graduation->delete();

        return redirect()
            ->route('tu.graduations.index')
            ->with('success', 'Data kelulusan siswa berhasil dihapus.');
    }

    public function importClassNine(): RedirectResponse
    {
        $existingStudentIds = Graduation::pluck('student_id')->toArray();

        $students = Student::query()
            ->whereNotIn('id', $existingStudentIds)
            ->whereHas('classroom', function ($q) {
                $q->where('name', 'like', '9%')
                    ->orWhere('name', 'like', 'IX%');
            })
            ->with('classroom')
            ->get();

        if ($students->isEmpty()) {
            return redirect()
                ->route('tu.graduations.index')
                ->with('error', 'Tidak ada siswa kelas 9 baru yang belum terdaftar.');
        }

        $prefix = Setting::where('key', 'graduation_letter_number_prefix')->value('value') ?? '421.3/SKL/{NO}/SMP-02/'.date('Y');
        $currentCount = Graduation::count();

        foreach ($students as $index => $st) {
            $letterNo = str_replace('{NO}', str_pad($currentCount + $index + 1, 3, '0', STR_PAD_LEFT), $prefix);

            Graduation::create([
                'student_id' => $st->id,
                'nis' => $st->nis,
                'nisn' => $st->nisn,
                'student_name' => $st->student_name,
                'classroom_name' => $st->classroom->name ?? '-',
                'letter_number' => $letterNo,
                'status' => 'lulus',
                'average_score' => 82.50,
                'graduation_date' => now()->format('Y-m-d'),
                'verification_code' => strtoupper(Str::random(12)),
            ]);

            $st->update(['student_status' => 'Graduated']);
        }

        return redirect()
            ->route('tu.graduations.index')
            ->with('success', "Berhasil mengimpor {$students->count()} siswa kelas 9 ke daftar kelulusan.");
    }

    public function updateSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'graduation_status' => ['required', 'in:0,1'],
            'graduation_announcement_date' => ['nullable', 'date'],
            'graduation_letter_number_prefix' => ['required', 'string', 'max:100'],
            'graduation_headmaster_name' => ['required', 'string', 'max:255'],
            'graduation_headmaster_nip' => ['nullable', 'string', 'max:50'],
        ]);

        foreach ($validated as $key => $val) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $val]
            );
        }

        return redirect()
            ->route('tu.graduations.index')
            ->with('success', 'Pengaturan pengumuman kelulusan berhasil disimpan.');
    }
}
