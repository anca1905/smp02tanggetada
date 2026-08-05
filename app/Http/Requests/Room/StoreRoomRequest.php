<?php

namespace App\Http\Requests\Room;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class StoreRoomRequest extends FormRequest
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
            "room_name" => ["required", "string", "max:100"],
            "location" => ["required", "string", "max:100"],
            "description" => ["nullable", "string"],
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
            "room_name.required" => "Nama ruangan harus diisi.",
            "room_name.string" => "Nama ruangan harus berupa string/karakter.",
            "room_name.max" =>
                "Nama ruangan tidak boleh lebih dari 100 karakter.",
            "location.required" => "Lokasi harus diisi.",
            "location.string" => "Lokasi harus berupa string/karakter.",
            "location.max" => "Lokasi tidak boleh lebih dari 100 karakter.",
            "description.string" => "Deskripsi harus berupa string.",
        ];
    }
}
