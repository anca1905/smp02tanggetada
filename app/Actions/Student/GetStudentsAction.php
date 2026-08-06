<?php

namespace App\Actions\Student;

use App\Models\Student;
use Illuminate\Pagination\LengthAwarePaginator;

class GetStudentsAction
{
    /**
     * Execute the action
     */
    public function execute(
        ?string $search,
        ?string $filterClass,
        int $paginator = 10,
    ): LengthAwarePaginator {
        $query = Student::with('classroom');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('student_name', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%")
                    ->orWhereHas('classroom', function ($qc) use ($search) {
                        $qc->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($filterClass) {
            $query->where('classroom_id', $filterClass);
        }

        return $query
            ->orderBy('student_name', 'asc')
            ->paginate($paginator)
            ->withQueryString();
    }
}
