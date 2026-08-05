<?php

namespace App\Http\Requests\Classroom;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class UpdateClassroomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => ["sometimes", "string"],
            "level" => ["sometimes", "string"],
            "teacher" => ["sometimes", "exists:teachers,id"],
        ];
    }

    /**
     * Customize the validation messages for the request.
     *
     * @return array<string, string>
     */
    #[Override]
    public function messages()
    {
        return [
            "name.string" => "Nama kelas harus berupa string/karakter.",

            "level.string" => "Level kelas harus berupa string/karakter.",

            "academic_year_id.exists" =>
                "Tahun akademik harus ada dalam daftar.",

            "teacher_id.exists" => "Guru harus ada dalam daftar.",
        ];
    }
}
