<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class GenerateQrRequest extends FormRequest
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
            'class' => ['required', 'exists:classrooms,id'],
            'session_type' => ['nullable', 'string', 'in:apel,kelas,pulang'],
            'subject_id' => ['nullable', 'required_if:session_type,kelas', 'exists:subjects,id'],
            'date' => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'class.required' => 'Kelas harus dipilih.',
            'class.exists' => 'Kelas tidak ditemukan.',
            'subject_id.required_if' => 'Mata pelajaran harus dipilih untuk sesi kelas.',
            'subject_id.exists' => 'Mata pelajaran tidak ditemukan.',
            'date.required' => 'Tanggal harus diisi.',
            'date.date' => 'Format tanggal tidak valid.',
        ];
    }
}
