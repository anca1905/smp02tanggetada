<?php

namespace App\Http\Controllers\Teacher;

use App\Models\Material;
use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\Schedule;
use App\Models\AcademicYear;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TeachingController extends Controller
{
    public function index()
    {
        $teacherId = Auth::guard('teacher')->id();

        $activeYear = AcademicYear::where('is_active', true)->first();

        if (!$activeYear) {
            return back()->with('error', 'Tahun ajaran aktif belum diset oleh Admin!');
        }

        $myClasses = Schedule::with(['classroom', 'subject'])
            ->where('teacher_id', $teacherId)
            ->whereHas('classroom', function ($q) use ($activeYear) {
                $q->where('academic_year_id', $activeYear->id);
            })
            ->get()
            ->groupBy(function ($data) {
                return $data->classroom->name . ' - ' . $data->subject->name;
            });

        return view('teacher.lms.index', compact('myClasses', 'activeYear'));
    }

    public function show($schedule_id)
    {
        $schedule = Schedule::with(['classroom', 'subject', 'classroom.students'])
            ->findOrFail($schedule_id);

        if ($schedule->teacher_id != Auth::guard('teacher')->id()) {
            abort(403);
        }

        $materials = Material::where('schedule_id', $schedule_id)
            ->latest()
            ->get();

        $assignments = Assignment::withCount('submissions')
            ->where('classroom_id', $schedule->classroom_id)
            ->where('subject_id', $schedule->subject_id)
            ->latest()
            ->get();

        return view('teacher.lms.course', compact('schedule', 'materials', 'assignments'));
    }

    public function storeMaterial(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required',
            'title' => 'required|string|max:255',
            'type' => 'required|in:pdf,youtube,link',
            'file' => 'nullable|required_if:type,pdf|mimes:pdf,doc,docx,ppt,pptx|max:10240', // Max 10MB
            'url' => 'nullable|required_if:type,youtube,link|url',
        ]);

        $filePath = null;
        $fileName = null;

        if ($request->type === 'pdf') {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $filePath = $file->store('materials', 'public');
        } else {
            $filePath = $request->url;
        }

        Material::create([
            'schedule_id' => $request->schedule_id,
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'file_path' => $filePath,
            'file_name' => $fileName,
        ]);

        return back()->with('success', 'Materi berhasil dibagikan!');
    }

    public function destroyMaterial($id)
    {
        $material = Material::findOrFail($id);

        if ($material->type === 'pdf' && $material->file_path) {
            Storage::disk('public')->delete($material->file_path);
        }

        $material->delete();
        return back()->with('success', 'Materi dihapus.');
    }

    public function storeAssignment(Request $request)
    {
        $request->validate([
            'classroom_id' => 'required',
            'subject_id' => 'required',
            'title' => 'required|string|max:255',
            'due_date' => 'required|date',
            'file' => 'nullable|file|max:10240',
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('assignments', 'public');
        }

        Assignment::create([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'file_path' => $filePath,
            'classroom_id' => $request->classroom_id,
            'subject_id' => $request->subject_id,
            'teacher_id' => Auth::guard('teacher')->id(),
        ]);

        return back()->with('success', 'Tugas berhasil dipublish!');
    }

    public function destroyAssignment($id)
    {
        $assignment = Assignment::findOrFail($id);
        if ($assignment->file_path) {
            Storage::disk('public')->delete($assignment->file_path);
        }
        $assignment->delete();
        return back()->with('success', 'Tugas berhasil dihapus.');
    }

    public function viewSubmissions($assignment_id)
    {
        $assignment = Assignment::with(['submissions.student'])->findOrFail($assignment_id);
        return view('teacher.lms.submissions', compact('assignment'));
    }

    public function gradeSubmission(Request $request, $submission_id)
    {
        $request->validate([
            'score' => 'required|integer|min:0|max:100',
        ]);

        $submission = AssignmentSubmission::findOrFail($submission_id);
        $submission->update([
            'score' => $request->score,
            'teacher_feedback' => $request->feedback,
        ]);

        return back()->with('success', 'Nilai berhasil disimpan!');
    }
}
