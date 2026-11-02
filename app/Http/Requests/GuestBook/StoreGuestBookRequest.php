<?php

namespace App\Http\Requests\GuestBook;

use Illuminate\Foundation\Http\FormRequest;

class StoreGuestBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'institution' => ['nullable', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'category' => ['required', 'string', 'max:50'],
            'meet_with' => ['required', 'string', 'max:50'],
            'purpose' => ['required', 'string', 'max:1000'],
            'visit_date' => ['required', 'date'],
            'visit_time' => ['nullable', 'string', 'max:10'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi.',
            'phone.required' => 'Nomor HP / WhatsApp wajib diisi.',
            'category.required' => 'Kategori tamu wajib dipilih.',
            'meet_with.required' => 'Tujuan bertemu wajib dipilih.',
            'purpose.required' => 'Keperluan / tujuan kunjungan wajib diisi.',
            'visit_date.required' => 'Tanggal kunjungan wajib diisi.',
        ];
    }
}
