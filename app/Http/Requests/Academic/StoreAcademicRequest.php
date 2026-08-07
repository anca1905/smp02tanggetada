<?php

namespace App\Http\Requests\Academic;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class StoreAcademicRequest extends FormRequest
{
    /**
     * Validasi atribut yang dikirimkan dalam request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validasi atribut yang dikirimkan dalam request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'semester' => ['required', 'in:Ganjil,Genap'],
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
            'name.required' => 'Nama tahun ajaran tidak boleh kosong',
            'name.string' => 'Nama tahun ajaran harus berupa string/karakter',

            'semester.required' => 'Semester tidak boleh kosong',
            'semester.in' => 'Semester harus berupa Ganjil atau Genap',
        ];
    }
}
