<?php

namespace App\Http\Requests\Facility;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class UpdateFacilityRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:100'],
            'image_path' => ['nullable', 'image', 'max:2048'],
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array<string, string>
     */
    #[Override]
    public function messages(): array
    {
        return [
            'title.required' => 'Nama fasilitas harus diisi.',
            'title.string' => 'Nama fasilitas harus berupa string/karakter.',
            'title.max' => 'Nama fasilitas tidak boleh lebih dari 100 karakter.',
            'image_path.image' => 'File yang diupload harus berupa gambar.',
            'image_path.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
        ];
    }
}
