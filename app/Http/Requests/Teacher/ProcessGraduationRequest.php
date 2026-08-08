<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class ProcessGraduationRequest extends FormRequest
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
            'status' => ['required', 'array'],
            'status.*' => ['required', 'string', 'in:Lulus,Tidak Lulus'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Status kelulusan harus diisi.',
            'status.array' => 'Format status tidak valid.',
            'status.*.required' => 'Setiap status siswa harus diisi.',
            'status.*.string' => 'Format status harus berupa teks.',
            'status.*.in' => 'Status harus berupa "Lulus" atau "Tidak Lulus".',
        ];
    }
}
