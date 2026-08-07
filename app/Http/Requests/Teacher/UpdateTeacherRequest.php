<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Override;

class UpdateTeacherRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Atur aturan validasi untuk request ini.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:100'],
            'gender' => ['sometimes', 'in:Male,Female'],
            'employee_id' => [
                'sometimes',
                Rule::unique('teachers', 'employee_id')->ignore(
                    $this->route('teacher'),
                ),
            ],
            'phone' => [
                'sometimes',
                'string',
                'max:20',
                'regex:/^(0|62|\+62)8[1-9][0-9]{6,11}$/',
            ],
            'subject' => ['nullable', 'string'],
            'homeroom_class' => ['nullable', 'string'],
            'status' => ['sometimes', 'in:Active,Inactive'],
            'username' => [
                'sometimes',
                'string',
                'max:100',
                Rule::unique('teachers', 'username')->ignore(
                    $this->route('teacher'),
                ),
            ],
            'password' => ['sometimes', 'string', 'min:6'],
            'photo_url' => ['nullable', 'image', 'max:2048'],
        ];
    }

    /**
     * Pesan validasi untuk request ini.
     *
     * @return array
     */
    #[Override]
    public function messages()
    {
        return [
            'name.string' => 'Nama guru harus berupa string/karakter',
            'name.max' => 'Nama guru tidak boleh lebih dari 100 karakter',

            'gender.in' => 'Jenis kelamin harus berupa Male (Laki-laki) atau Female (Perempuan)',

            'employee_id.unique' => 'Nomor pegawai sudah terdaftar',

            'phone.string' => 'Nomor telepon harus berupa string/karakter',
            'phone.max' => 'Nomor telepon tidak boleh lebih dari 20 karakter',
            'phone.regex' => 'Nomor telepon harus dalam format indonesia (08xxxxxxxx atau +628xxxxxxxx).',

            'subject.string' => 'Mata pelajaran harus berupa string/karakter',

            'homeroom_class.string' => 'Kelas ruang guru harus berupa string/karakter',

            'status.in' => 'Status harus berupa Active (Aktif) atau Inactive (Tidak Aktif)',

            'username.string' => 'Username harus berupa string/karakter',
            'username.max' => 'Username tidak boleh lebih dari 100 karakter',
            'username.unique' => 'Username sudah terdaftar',

            'password.string' => 'Password harus berupa string/karakter',
            'password.min' => 'Password harus minimal 6 karakter',

            'photo_url.image' => 'Foto harus berupa gambar',
            'photo_url.max' => 'Foto tidak boleh lebih dari 2 megabytes',
        ];
    }
}
