<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;

class ProcessPromotionRequest extends FormRequest
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
            'action' => 'required|array',
            'next_classroom_id' => 'required|integer|exists:classrooms,id',
        ];
    }

    public function messages(): array
    {
        return [
            'action.required' => 'Aksi kenaikan kelas harus dipilih.',
            'action.array' => 'Format aksi tidak valid.',
            'next_classroom_id.required' => 'Kelas tujuan harus dipilih.',
            'next_classroom_id.integer' => 'ID kelas tujuan tidak valid.',
            'next_classroom_id.exists' => 'Kelas tujuan tidak ditemukan.',
        ];
    }
}
