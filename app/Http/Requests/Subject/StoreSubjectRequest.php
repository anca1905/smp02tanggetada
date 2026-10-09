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
            'cover' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
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

            'cover.image' => 'Cover harus berupa file gambar.',
            'cover.mimes' => 'Format cover harus jpeg, png, jpg, atau webp.',
            'cover.max' => 'Ukuran file cover maksimal 2MB.',
        ];
    }
}
