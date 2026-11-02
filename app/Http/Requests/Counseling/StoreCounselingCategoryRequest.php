<?php

namespace App\Http\Requests\Counseling;

use Illuminate\Foundation\Http\FormRequest;

class StoreCounselingCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:violation,achievement'],
            'point' => ['required', 'integer', 'min:1', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama kategori wajib diisi.',
            'type.required' => 'Tipe kategori wajib dipilih.',
            'point.required' => 'Poin default wajib diisi.',
            'point.min' => 'Poin minimal adalah 1.',
        ];
    }
}
