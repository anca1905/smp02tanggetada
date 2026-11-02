<?php

namespace App\Http\Requests\Counseling;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCounselingRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'exists:students,id'],
            'counseling_category_id' => ['nullable', 'exists:counseling_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:violation,achievement'],
            'point' => ['required', 'integer', 'min:0'],
            'incident_date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:2000'],
            'action_taken' => ['nullable', 'string', 'max:2000'],
            'status' => ['required', 'in:pending,in_progress,resolved'],
            'reported_by' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'student_id.required' => 'Siswa harus dipilih.',
            'student_id.exists' => 'Siswa tidak valid.',
            'title.required' => 'Judul / Uraian singkat wajib diisi.',
            'type.required' => 'Tipe catatan wajib dipilih.',
            'point.required' => 'Poin wajib diisi.',
            'incident_date.required' => 'Tanggal kejadian wajib diisi.',
        ];
    }
}
