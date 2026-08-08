<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class StoreAssignmentRequest extends FormRequest
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
            'classroom_id' => 'required',
            'subject_id' => 'required',
            'title' => 'required|string|max:255',
            'due_date' => 'required|date',
            'file' => 'nullable|file|max:10240',
        ];
    }

    public function messages(): array
    {
        return [
            'classroom_id.required' => 'Kelas harus dipilih.',
            'subject_id.required' => 'Mata pelajaran harus dipilih.',
            'title.required' => 'Judul tugas harus diisi.',
            'title.string' => 'Judul harus berupa teks.',
            'title.max' => 'Judul tidak boleh lebih dari 255 karakter.',
            'due_date.required' => 'Batas waktu harus diisi.',
            'due_date.date' => 'Batas waktu harus berupa tanggal yang valid.',
            'file.file' => 'File yang diunggah harus berupa file.',
            'file.max' => 'Ukuran file tidak boleh lebih dari 10MB.',
        ];
    }
}
