<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class StudentLoginRequest extends FormRequest
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
            'nis' => 'required|string',
            'password' => 'required|string',
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            'nis.required' => 'NIS harus diisi.',
            'nis.string' => 'NIS harus berupa string/karakter.',
            'password.required' => 'Password harus diisi.',
            'password.string' => 'Password harus berupa string/karakter.',
        ];
    }
}
