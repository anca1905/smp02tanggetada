<?php

namespace App\Http\Requests\Public;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class StoreContactMessageRequest extends FormRequest
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
            "name" => "required|string|max:255",
            "email" => "required|email",
            "subject" => "required|string|max:255",
            "message" => "required|string",
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            "name.required" => "Name harus diisi.",
            "name.string" => "Name harus berupa string.",
            "name.max" => "Name tidak boleh lebih dari 255 karakter.",

            "email.required" => "Email harus diisi.",
            "email.email" => "Email tidak valid.",

            "subject.required" => "Subject harus diisi.",
            "subject.string" => "Subject harus berupa string.",
            "subject.max" => "Subject tidak boleh lebih dari 255 karakter.",

            "message.required" => "Message harus diisi.",
            "message.string" => "Message harus berupa string.",
        ];
    }
}
