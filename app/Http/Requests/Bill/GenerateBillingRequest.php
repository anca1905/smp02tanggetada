<?php

namespace App\Http\Requests\Bill;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class GenerateBillingRequest extends FormRequest
{
    /**
     * Memeriksa apakah pengguna diizinkan untuk membuat permintaan ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi untuk setiap atribut.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'month' => ['required', 'string'],
            'amount' => ['required', 'numeric', 'min:0'],
            'due_date' => ['required', 'date'],
        ];
    }

    /**
     * Pesan validasi untuk setiap atribut.
     *
     * @return array<string, string>
     */
    #[Override]
    public function messages()
    {
        return [
            'month.required' => 'Bulan harus diisi',
            'month.string' => 'Bulan harus berupa string/karakter',

            'amount.required' => 'Jumlah harus diisi',
            'amount.numeric' => 'Jumlah harus berupa angka',
            'amount.min' => 'Jumlah harus lebih besar dari 0',

            'due_date.required' => 'Tanggal jatuh tempo harus diisi',
            'due_date.date' => 'Tanggal jatuh tempo harus berupa tanggal (YYYY-MM-DD)',
        ];
    }
}
