<?php

namespace App\Http\Requests\Achievement;

use Illuminate\Foundation\Http\FormRequest;

class StoreAchievementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'student_name' => ['required', 'string', 'max:255'],
            'student_id' => ['nullable', 'exists:students,id'],
            'category' => ['required', 'string', 'max:50'],
            'level' => ['required', 'string', 'max:50'],
            'rank' => ['nullable', 'string', 'max:100'],
            'event_name' => ['required', 'string', 'max:255'],
            'event_date' => ['nullable', 'date'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
            'description' => ['nullable', 'string', 'max:2000'],
            'is_featured' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Judul kejuaraan / prestasi wajib diisi.',
            'student_name.required' => 'Nama siswa peraih wajib diisi.',
            'category.required' => 'Kategori bidang wajib dipilih.',
            'level.required' => 'Tingkat kejuaraan wajib dipilih.',
            'event_name.required' => 'Nama kegiatan / lomba wajib diisi.',
            'photo.image' => 'File dokumentasi harus berupa gambar.',
            'photo.max' => 'Ukuran foto maksimal 4MB.',
        ];
    }
}
