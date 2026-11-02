<?php

namespace App\Http\Requests\Graduation;

use Illuminate\Foundation\Http\FormRequest;

class CheckGraduationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'identity' => ['required', 'string', 'max:50'],
            'birth_date' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'identity.required' => 'NISN atau NIS wajib diisi untuk memeriksa kelulusan.',
        ];
    }
}
