<?php

namespace App\Http\Requests\Teacher;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class StoreTeacherRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:100'],
            'gender' => ['required', 'in:Male,Female'],
            'employee_id' => ['required', 'unique:teachers,employee_id'],
            'phone' => [
                'required',
                'string',
                'max:20',
                'regex:/^(0|62|\+62)8[1-9][0-9]{6,11}$/',
            ],
            'subject' => ['nullable', 'string'],
            'homeroom_class' => ['nullable', 'string'],
            'status' => ['required', 'in:Active,Inactive'],
            'username' => [
                'required',
                'string',
                'max:100',
                'unique:teachers,username',
            ],
            'password' => ['required', 'string', 'min:6'],
            'photo_url' => ['nullable', 'image', 'max:2048'],
        ];
    }

    /**
     * Get the validation messages for the request.
     *
     * @return array
     */
    #[Override]
    public function messages()
    {
        return [
            'name.required' => 'Nama guru harus diisi',
            'name.string' => 'Nama guru harus berupa string/karakter',
            'name.max' => 'Nama guru tidak boleh lebih dari 100 karakter',

            'gender.required' => 'Jenis kelamin harus diisi',
            'gender.in' => 'Jenis kelamin harus berupa Male (Laki-laki) atau Female (Perempuan)',

            'employee_id.required' => 'Nomor pegawai harus diisi',
            'employee_id.unique' => 'Nomor pegawai sudah terdaftar',

            'phone.required' => 'Nomor telepon harus diisi',
            'phone.string' => 'Nomor telepon harus berupa string/karakter',
            'phone.max' => 'Nomor telepon tidak boleh lebih dari 20 karakter',
            'phone.regex' => 'Nomor telepon harus dalam format indonesia (08xxxxxxxx atau +628xxxxxxxx).',

            'subject.string' => 'Mata pelajaran harus berupa string/karakter',

            'homeroom_class.string' => 'Kelas ruang guru harus berupa string/karakter',

            'status.required' => 'Status harus diisi',
            'status.in' => 'Status harus berupa Active (Aktif) atau Inactive (Tidak Aktif)',

            'username.required' => 'Username harus diisi',
            'username.string' => 'Username harus berupa string/karakter',
            'username.max' => 'Username tidak boleh lebih dari 100 karakter',
            'username.unique' => 'Username sudah terdaftar',

            'password.required' => 'Password harus diisi',
            'password.string' => 'Password harus berupa string/karakter',
            'password.min' => 'Password harus minimal 6 karakter',

            'photo_url.image' => 'Foto harus berupa gambar',
            'photo_url.max' => 'Foto tidak boleh lebih dari 2 megabytes',
        ];
    }
}
