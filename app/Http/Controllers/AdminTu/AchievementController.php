<?php

namespace App\Http\Controllers\AdminTu;

use App\Http\Controllers\Controller;
use App\Http\Requests\Achievement\StoreAchievementRequest;
use App\Http\Requests\Achievement\UpdateAchievementRequest;
use App\Models\Achievement;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AchievementController extends Controller
{
    public function index(Request $request): View
    {
        $query = Achievement::query()->with('student.classroom')->latest('event_date')->latest('id');

        if ($request->filled('category')) {
            $query->where('category', $request->query('category'));
        }

        if ($request->filled('level')) {
            $query->where('level', $request->query('level'));
        }

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('student_name', 'like', "%{$search}%")
                    ->orWhere('event_name', 'like', "%{$search}%")
                    ->orWhere('rank', 'like', "%{$search}%");
            });
        }

        $achievements = $query->paginate(12)->withQueryString();

        $students = Student::query()
            ->select(['id', 'nis', 'student_name', 'classroom_id'])
            ->with('classroom')
            ->orderBy('student_name')
            ->get();

        $stats = [
            'total' => Achievement::count(),
            'kabupaten' => Achievement::where('level', 'kabupaten')->count(),
            'provinsi' => Achievement::where('level', 'provinsi')->count(),
            'nasional' => Achievement::whereIn('level', ['nasional', 'internasional'])->count(),
            'featured' => Achievement::where('is_featured', true)->count(),
        ];

        return view('tu.achievements.index', compact('achievements', 'students', 'stats'));
    }

    public function store(StoreAchievementRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('achievements', 'public');
        }

        $data['is_featured'] = $request->boolean('is_featured');

        // Auto-fill student_name if student_id selected and student_name is empty
        if (! empty($data['student_id']) && empty($data['student_name'])) {
            $student = Student::find($data['student_id']);
            $data['student_name'] = $student?->student_name ?? '';
        }

        Achievement::create($data);

        return redirect()
            ->route('tu.achievements.index')
            ->with('success', 'Prestasi siswa berhasil ditambahkan.');
    }

    public function update(UpdateAchievementRequest $request, Achievement $achievement): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            if ($achievement->photo_path && Storage::disk('public')->exists($achievement->photo_path)) {
                Storage::disk('public')->delete($achievement->photo_path);
            }
            $data['photo_path'] = $request->file('photo')->store('achievements', 'public');
        }

        $data['is_featured'] = $request->boolean('is_featured');

        $achievement->update($data);

        return redirect()
            ->route('tu.achievements.index')
            ->with('success', 'Data prestasi siswa berhasil diperbarui.');
    }

    public function destroy(Achievement $achievement): RedirectResponse
    {
        if ($achievement->photo_path && Storage::disk('public')->exists($achievement->photo_path)) {
            Storage::disk('public')->delete($achievement->photo_path);
        }

        $achievement->delete();

        return redirect()
            ->route('tu.achievements.index')
            ->with('success', 'Prestasi siswa berhasil dihapus.');
    }

    public function toggleFeatured(Achievement $achievement): RedirectResponse
    {
        $achievement->update([
            'is_featured' => ! $achievement->is_featured,
        ]);

        return redirect()
            ->route('tu.achievements.index')
            ->with('success', 'Status sorotan prestasi berhasil diperbarui.');
    }
}
