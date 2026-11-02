<?php

namespace App\Http\Requests\Extracurricular;

use Illuminate\Foundation\Http\FormRequest;

class StoreExtracurricularRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'extracurricular_id' => ['required', 'exists:extracurriculars,id'],
            'student_id' => ['nullable', 'exists:students,id'],
            'student_name' => ['required', 'string', 'max:255'],
            'classroom_name' => ['nullable', 'string', 'max:100'],
            'phone' => ['required', 'string', 'max:30'],
            'reason' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'extracurricular_id.required' => 'Pilihan ekstrakurikuler wajib dipilih.',
            'extracurricular_id.exists' => 'Ekstrakurikuler yang dipilih tidak valid.',
            'student_name.required' => 'Nama lengkap siswa wajib diisi.',
            'phone.required' => 'Nomor WhatsApp / HP siswa atau orang tua wajib diisi.',
        ];
    }
}
