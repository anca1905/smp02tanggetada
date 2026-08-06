<?php

namespace App\Http\Requests\Borrowing;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class UpdateBorrowingRequest extends FormRequest
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
            "full_name" => ["sometimes", "string", "max:100"],
            "room_type" => ["sometimes", "string"],
            "borrow_date" => ["sometimes", "date"],
            "start_time" => ["sometimes", "string"],
            "end_time" => ["sometimes", "string", "after:start_time"],
            "status" => ["sometimes", "in:upcoming,ongoing,completed"],
        ];
    }

    /**
     * Pesan validasi khusus kustom
     *
     * @return array<string, string>
     */
    #[Override]
    public function messages()
    {
        return [
            "full_name.string" => "Nama peminjam harus berupa string/karakter.",
            "full_name.max" =>
                "Nama peminjam tidak boleh lebih dari 100 karakter.",

            "room_type.string" => "Jenis ruangan harus berupa string/karakter.",

            "borrow_date.date" => "Tanggal peminjaman harus berupa tanggal.",

            "start_time.string" => "Jam mulai harus berupa string/karakter.",

            "end_time.string" => "Jam selesai harus berupa string/karakter.",
            "end_time.after" => "Jam selesai harus setelah jam mulai.",

            "status.in" => "Status peminjaman tidak valid.",
        ];
    }
}
