<?php

namespace App\Http\Requests\Subject;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class StoreSubjectRequest extends FormRequest
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
            'code' => ['required', 'string', 'unique:subjects,code'],
            'name' => ['required', 'string'],
        ];
    }

    /**
     * Get the validation error messages for the request.
     *
     * @return array<string, string>
     */
    #[Override]
    public function messages(): array
    {
        return [
            'code.required' => 'Kode mata pelajaran harus diisi.',
            'code.string' => 'Kode mata pelajaran harus berupa string/karakter.',
            'code.unique' => 'Kode mata pelajaran sudah terdaftar.',

            'name.required' => 'Nama mata pelajaran harus diisi.',
            'name.string' => 'Nama mata pelajaran harus berupa string/karakter.',
        ];
    }
}
