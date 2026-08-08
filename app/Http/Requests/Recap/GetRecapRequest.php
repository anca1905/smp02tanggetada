<?php

namespace App\Http\Requests\Recap;

use Illuminate\Foundation\Http\FormRequest;

class GetRecapRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'kategori' => 'nullable|in:teacher,student',
            'bulan' => 'nullable|integer|between:1,12',
            'search' => 'nullable|string|max:100',
            'class' => 'nullable|exists:classrooms,id',
        ];
    }

    public function messages(): array
    {
        return [
            'kategori.in' => 'Kategori tidak valid.',
            'bulan.integer' => 'Bulan harus berupa angka.',
            'bulan.between' => 'Bulan harus antara 1 sampai 12.',
            'search.string' => 'Pencarian harus berupa string.',
            'search.max' => 'Pencarian tidak boleh lebih dari 100 karakter.',
            'class.exists' => 'Kelas tidak ditemukan.',
        ];
    }
}
