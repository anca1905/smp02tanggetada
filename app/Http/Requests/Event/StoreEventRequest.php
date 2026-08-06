<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class StoreEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Asumsikan middleware auth sudah menangani otorisasi
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "title" => ["required", "string", "max:255"],
            "start_date" => ["required", "date"],
            "end_date" => ["nullable", "date", "after_or_equal:start_date"],
            "type" => ["required", "string"],
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            "title.required" => "Judul wajib diisi.",
            "title.string" => "Judul harus berupa string.",
            "title.max" => "Judul tidak boleh lebih dari 255 karakter.",

            "start_date.required" => "Tanggal mulai wajib diisi.",
            "start_date.date" => "Tanggal mulai harus berupa tanggal.",

            "end_date.after_or_equal" =>
                "Tanggal akhir harus setelah atau sama dengan tanggal mulai.",

            "type.required" => "Jenis wajib diisi.",
            "type.string" => "Jenis harus berupa string.",
        ];
    }
}
