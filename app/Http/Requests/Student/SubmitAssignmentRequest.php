<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class SubmitAssignmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'assignment_id' => ['required'],
            'file' => ['required', 'file', 'max:10240'], // Max 10MB
            'note' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'assignment_id.required' => 'Assignment ID harus diisi.',
            'file.required' => 'File harus diunggah.',
            'file.file' => 'File yang diunggah harus berupa file.',
            'file.max' => 'Ukuran file tidak boleh lebih dari 10MB.',
            'note.string' => 'Catatan harus berupa teks.',
        ];
    }
}
