<?php

namespace App\Http\Requests\Ppdb;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class UpdatePpdbStatusRequest extends FormRequest
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
            'status_pendaftaran' => 'required|in:Pending,Accepted,Rejected',
        ];
    }

    /**
     * Get the validation error messages.
     *
     * @return array<string, string>
     */
    #[Override]
    public function messages()
    {
        return [
            'status_pendaftaran.required' => 'Status pendaftaran harus diisi.',
            'status_pendaftaran.in' => 'Status pendaftaran tidak valid.',
        ];
    }
}
