<?php

namespace App\Http\Requests\Extracurricular;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExtracurricularRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:50'],
            'instructor_name' => ['nullable', 'string', 'max:255'],
            'schedule_day' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama kegiatan ekstrakurikuler wajib diisi.',
            'category.required' => 'Kategori ekstrakurikuler wajib dipilih.',
            'photo.image' => 'File poster/foto harus berupa gambar.',
            'photo.max' => 'Ukuran gambar maksimal 3MB.',
        ];
    }
}
