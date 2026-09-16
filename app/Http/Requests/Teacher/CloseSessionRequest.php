<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class CloseSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'session_type' => ['required', 'string', 'in:apel,kelas,pulang'],
            'class' => ['required', 'exists:classrooms,id'],
            'date' => ['required', 'date'],
            'subject_id' => ['nullable', 'exists:subjects,id'],
        ];
    }
}
