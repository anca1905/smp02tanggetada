<?php

namespace App\Http\Requests\Borrowing;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class StoreBorrowingRequest extends FormRequest
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
            "full_name" => ["required", "string", "max:100"],
            "nis" => ["nullable", "string", "max:20"],
            "class" => ["nullable", "string", "max:10"],
            "phone_number" => [
                "nullable",
                "string",
                "max:20",
                'regex:/^(0|62|\+62)8[1-9][0-9]{6,11}$/',
            ],
            "room_type" => ["required", "string"],
            "borrow_date" => ["required", "date"],
            "start_time" => ["required", "string"],
            "end_time" => ["required", "string", "after:start_time"],
            "activity_description" => ["required", "string"],
            "responsible_person" => ["required", "string", "max:100"],
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
            "full_name.required" => "Nama peminjam tidak boleh kosong.",
            "full_name.string" => "Nama peminjam harus berupa string/karakter.",
            "full_name.max" =>
                "Nama peminjam tidak boleh lebih dari 100 karakter.",

            "nis.string" => "NIS harus berupa string/karakter.",
            "nis.max" => "NIS tidak boleh lebih dari 20 karakter.",

            "class.string" => "Kelas harus berupa string/karakter.",
            "class.max" => "Kelas tidak boleh lebih dari 10 karakter.",

            "phone_number.string" =>
                "Nomor telepon harus berupa string/karakter.",
            "phone_number.max" =>
                "Nomor telepon tidak boleh lebih dari 20 karakter.",
            "phone_number.regex" =>
                "Nomor telepon harus dalam format Indonesia (08xxxxxxxx atau +628xxxxxxxx).",

            "room_type.required" => "Jenis ruangan wajib dipilih.",
            "room_type.string" => "Jenis ruangan harus berupa string/karakter.",

            "borrow_date.required" => "Tanggal peminjaman wajib diisi.",
            "borrow_date.date" => "Tanggal peminjaman harus berupa tanggal.",

            "start_time.required" => "Jam mulai wajib diisi.",
            "start_time.string" => "Jam mulai harus berupa string/karakter.",

            "end_time.required" => "Jam selesai wajib diisi.",
            "end_time.string" => "Jam selesai harus berupa string/karakter.",
            "end_time.after" => "Jam selesai harus setelah jam mulai.",

            "activity_description.required" =>
                "Deskripsi kegiatan wajib diisi.",
            "activity_description.string" =>
                "Deskripsi kegiatan harus berupa string/karakter.",

            "responsible_person.required" =>
                "Nama penanggung jawab wajib diisi.",
            "responsible_person.string" =>
                "Nama penanggung jawab harus berupa string/karakter.",
            "responsible_person.max" =>
                "Nama penanggung jawab tidak boleh lebih dari 100 karakter.",
        ];
    }
}
