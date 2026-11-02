<?php

namespace App\Http\Requests\Graduation;

use Illuminate\Foundation\Http\FormRequest;

class StoreGraduationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'exists:students,id'],
            'nisn' => ['nullable', 'string', 'max:20'],
            'letter_number' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'in:lulus,ditunda,tidak_lulus'],
            'average_score' => ['required', 'numeric', 'min:0', 'max:100'],
            'graduation_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'student_id.required' => 'Siswa harus dipilih.',
            'status.required' => 'Status kelulusan wajib dipilih.',
            'average_score.required' => 'Nilai rata-rata ujian wajib diisi.',
            'average_score.min' => 'Nilai minimal 0.',
            'average_score.max' => 'Nilai maksimal 100.',
        ];
    }
}
