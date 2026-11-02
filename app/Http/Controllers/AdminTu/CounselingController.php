<?php

namespace App\Http\Controllers\AdminTu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Counseling\StoreCounselingCategoryRequest;
use App\Http\Requests\Counseling\StoreCounselingRecordRequest;
use App\Http\Requests\Counseling\UpdateCounselingRecordRequest;
use App\Models\Classroom;
use App\Models\CounselingCategory;
use App\Models\Student;
use App\Models\StudentCounselingRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CounselingController extends Controller
{
    public function index(Request $request): View
    {
        $activeTab = $request->query('tab', 'records');

        // Query for counseling records
        $recordsQuery = StudentCounselingRecord::query()
            ->with(['student.classroom', 'category'])
            ->latest('incident_date');

        if ($request->filled('type')) {
            $recordsQuery->where('type', $request->query('type'));
        }

        if ($request->filled('status')) {
            $recordsQuery->where('status', $request->query('status'));
        }

        if ($request->filled('classroom_id')) {
            $recordsQuery->whereHas('student', function ($q) use ($request) {
                $q->where('classroom_id', $request->query('classroom_id'));
            });
        }

        if ($request->filled('search')) {
            $search = $request->query('search');
            $recordsQuery->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('student', function ($sq) use ($search) {
                        $sq->where('student_name', 'like', "%{$search}%")
                            ->orWhere('nis', 'like', "%{$search}%");
                    });
            });
        }

        $records = $recordsQuery->paginate(15)->withQueryString();

        // Data for student recap / discipline points
        $studentsRecap = Student::query()
            ->with('classroom')
            ->withSum([
                'counselingRecords as total_violation_points' => function ($q) {
                    $q->where('type', 'violation');
                },
            ], 'point')
            ->withSum([
                'counselingRecords as total_achievement_points' => function ($q) {
                    $q->where('type', 'achievement');
                },
            ], 'point')
            ->withCount([
                'counselingRecords as violation_count' => function ($q) {
                    $q->where('type', 'violation');
                },
                'counselingRecords as achievement_count' => function ($q) {
                    $q->where('type', 'achievement');
                },
            ]);

        if ($request->filled('classroom_id')) {
            $studentsRecap->where('classroom_id', $request->query('classroom_id'));
        }

        if ($request->filled('search') && $activeTab === 'students') {
            $search = $request->query('search');
            $studentsRecap->where(function ($q) use ($search) {
                $q->where('student_name', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        $studentsWithPoints = $studentsRecap
            ->orderByDesc('total_violation_points')
            ->paginate(15, ['*'], 'student_page')
            ->withQueryString();

        // Master categories
        $categories = CounselingCategory::query()
            ->withCount('records')
            ->orderBy('type')
            ->orderBy('name')
            ->get();

        $classrooms = Classroom::query()->orderBy('name')->get();
        $students = Student::query()->select(['id', 'nis', 'student_name', 'classroom_id'])->with('classroom')->orderBy('student_name')->get();

        $stats = [
            'total_violations' => StudentCounselingRecord::where('type', 'violation')->count(),
            'total_achievements' => StudentCounselingRecord::where('type', 'achievement')->count(),
            'pending_cases' => StudentCounselingRecord::where('status', '!=', 'resolved')->count(),
            'students_with_violations' => Student::whereHas('counselingRecords', function ($q) {
                $q->where('type', 'violation');
            })->count(),
        ];

        return view('tu.counseling.index', compact(
            'records',
            'studentsWithPoints',
            'categories',
            'classrooms',
            'students',
            'stats',
            'activeTab'
        ));
    }

    public function storeRecord(StoreCounselingRecordRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Default point from category if 0 or not provided
        if (empty($data['point']) && ! empty($data['counseling_category_id'])) {
            $cat = CounselingCategory::find($data['counseling_category_id']);
            $data['point'] = $cat?->point ?? 0;
        }

        StudentCounselingRecord::create($data);

        return redirect()
            ->route('tu.counseling.index', ['tab' => 'records'])
            ->with('success', 'Catatan konseling / kedisiplinan berhasil ditambahkan.');
    }

    public function updateRecord(UpdateCounselingRecordRequest $request, StudentCounselingRecord $record): RedirectResponse
    {
        $record->update($request->validated());

        return redirect()
            ->route('tu.counseling.index', ['tab' => 'records'])
            ->with('success', 'Catatan konseling berhasil diperbarui.');
    }

    public function destroyRecord(StudentCounselingRecord $record): RedirectResponse
    {
        $record->delete();

        return redirect()
            ->route('tu.counseling.index', ['tab' => 'records'])
            ->with('success', 'Catatan konseling berhasil dihapus.');
    }

    public function storeCategory(StoreCounselingCategoryRequest $request): RedirectResponse
    {
        CounselingCategory::create($request->validated());

        return redirect()
            ->route('tu.counseling.index', ['tab' => 'categories'])
            ->with('success', 'Kategori konseling berhasil ditambahkan.');
    }

    public function destroyCategory(CounselingCategory $category): RedirectResponse
    {
        $category->delete();

        return redirect()
            ->route('tu.counseling.index', ['tab' => 'categories'])
            ->with('success', 'Kategori konseling berhasil dihapus.');
    }
}
