<?php

namespace App\Http\Requests\Classroom;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class StoreClassroomRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'level' => ['required', 'string'],
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'teacher_id' => ['required', 'exists:teachers,id'],
        ];
    }

    /**
     * Get the validation error messages for the request.
     *
     * @return array<string, string>
     */
    #[Override]
    public function messages()
    {
        return [
            'name.required' => 'Nama kelas tidak boleh kosong',

            'name.string' => 'Nama kelas harus berupa string/karakter.',

            'level.required' => 'Level kelas tidak boleh kosong',
            'level.string' => 'Level kelas harus berupa string/karakter.',

            'academic_year_id.required' => 'Tahun akademik tidak boleh kosong',
            'academic_year_id.exists' => 'Tahun akademik harus ada dalam daftar.',

            'teacher_id.required' => 'Guru tidak boleh kosong',
            'teacher_id.exists' => 'Guru harus ada dalam daftar.',
        ];
    }
}
