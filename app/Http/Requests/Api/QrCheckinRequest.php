<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class QrCheckinRequest extends FormRequest
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
            "qr_token" => "required|string",
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
            "qr_token.required" => "QR Code harus diisi.",
            "qr_token.string" => "QR Code harus berupa string/karakter.",
        ];
    }
}
