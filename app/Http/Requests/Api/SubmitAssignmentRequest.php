<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Override;

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
            "file" => "required|file|max:10240", // max 10MB
            "student_note" => "nullable|string",
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            "file.required" => "File harus diisi.",
            "file.file" => "File harus berupa file.",
            "file.max" => "File tidak boleh lebih dari 10MB.",
            "student_note.string" =>
                "Catatan siswa harus berupa string/karakter.",
        ];
    }
}
