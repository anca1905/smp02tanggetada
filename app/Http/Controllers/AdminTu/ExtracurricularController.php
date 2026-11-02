<?php

namespace App\Http\Controllers\AdminTu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Extracurricular\StoreExtracurricularRegistrationRequest;
use App\Http\Requests\Extracurricular\StoreExtracurricularRequest;
use App\Http\Requests\Extracurricular\UpdateExtracurricularRequest;
use App\Models\Extracurricular;
use App\Models\ExtracurricularRegistration;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ExtracurricularController extends Controller
{
    public function index(Request $request): View
    {
        $activeTab = $request->query('tab', 'list'); // 'list' or 'registrations'

        // 1. Extracurricular list
        $ekskulQuery = Extracurricular::query()->withCount(['registrations', 'acceptedRegistrations'])->latest('id');

        if ($request->filled('ekskul_search')) {
            $search = $request->query('ekskul_search');
            $ekskulQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('instructor_name', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $ekskulQuery->where('category', $request->query('category'));
        }

        $extracurriculars = $ekskulQuery->paginate(12, ['*'], 'ekskul_page')->withQueryString();

        // 2. Registrations list
        $regQuery = ExtracurricularRegistration::query()
            ->with(['extracurricular', 'student.classroom'])
            ->latest('id');

        if ($request->filled('reg_search')) {
            $search = $request->query('reg_search');
            $regQuery->where(function ($q) use ($search) {
                $q->where('student_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('classroom_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('reg_status')) {
            $regQuery->where('status', $request->query('reg_status'));
        }

        if ($request->filled('ekskul_id')) {
            $regQuery->where('extracurricular_id', $request->query('ekskul_id'));
        }

        $registrations = $regQuery->paginate(15, ['*'], 'reg_page')->withQueryString();

        // 3. Stats
        $stats = [
            'total_ekskul' => Extracurricular::count(),
            'total_registrations' => ExtracurricularRegistration::count(),
            'pending_registrations' => ExtracurricularRegistration::where('status', 'pending')->count(),
            'accepted_registrations' => ExtracurricularRegistration::where('status', 'accepted')->count(),
        ];

        $allEkskuls = Extracurricular::orderBy('name')->get();
        $students = Student::select(['id', 'nis', 'student_name', 'classroom_id', 'phone_number'])->with('classroom')->orderBy('student_name')->get();

        return view('tu.extracurriculars.index', compact('extracurriculars', 'registrations', 'stats', 'allEkskuls', 'students', 'activeTab'));
    }

    public function store(StoreExtracurricularRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('extracurricular_photos', 'public');
        }

        $data['is_active'] = $request->boolean('is_active', true);

        Extracurricular::create($data);

        return redirect()
            ->route('tu.extracurriculars.index', ['tab' => 'list'])
            ->with('success', 'Kegiatan ekstrakurikuler baru berhasil ditambahkan.');
    }

    public function update(UpdateExtracurricularRequest $request, Extracurricular $extracurricular): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            if ($extracurricular->photo_path && Storage::disk('public')->exists($extracurricular->photo_path)) {
                Storage::disk('public')->delete($extracurricular->photo_path);
            }
            $data['photo_path'] = $request->file('photo')->store('extracurricular_photos', 'public');
        }

        $data['is_active'] = $request->boolean('is_active', true);

        $extracurricular->update($data);

        return redirect()
            ->route('tu.extracurriculars.index', ['tab' => 'list'])
            ->with('success', 'Data ekstrakurikuler berhasil diperbarui.');
    }

    public function destroy(Extracurricular $extracurricular): RedirectResponse
    {
        if ($extracurricular->photo_path && Storage::disk('public')->exists($extracurricular->photo_path)) {
            Storage::disk('public')->delete($extracurricular->photo_path);
        }

        $extracurricular->delete();

        return redirect()
            ->route('tu.extracurriculars.index', ['tab' => 'list'])
            ->with('success', 'Ekstrakurikuler berhasil dihapus.');
    }

    public function storeRegistration(StoreExtracurricularRegistrationRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['status'] = 'accepted'; // When Admin TU registers a student, direct accept

        ExtracurricularRegistration::create($data);

        return redirect()
            ->route('tu.extracurriculars.index', ['tab' => 'registrations'])
            ->with('success', 'Pendaftaran siswa ke ekstrakurikuler berhasil ditambahkan.');
    }

    public function updateRegistrationStatus(Request $request, ExtracurricularRegistration $registration): RedirectResponse
    {
        $request->validate([
            'status' => ['required', 'in:pending,accepted,rejected'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $registration->update([
            'status' => $request->status,
            'notes' => $request->filled('notes') ? $request->notes : $registration->notes,
        ]);

        return redirect()
            ->route('tu.extracurriculars.index', ['tab' => 'registrations'])
            ->with('success', 'Status pendaftaran ekstrakurikuler berhasil diperbarui.');
    }

    public function destroyRegistration(ExtracurricularRegistration $registration): RedirectResponse
    {
        $registration->delete();

        return redirect()
            ->route('tu.extracurriculars.index', ['tab' => 'registrations'])
            ->with('success', 'Data pendaftaran berhasil dihapus.');
    }
}
