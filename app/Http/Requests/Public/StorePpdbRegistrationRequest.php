<?php

namespace App\Http\Requests\Public;

use Illuminate\Foundation\Http\FormRequest;

class StorePpdbRegistrationRequest extends FormRequest
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
            'nama_lengkap' => ['required', 'string', 'max:100'],
            'nisn' => ['required', 'string', 'max:20'],
            'nik' => ['required', 'string', 'max:20'],
            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
            'jurusan' => ['required', 'string', 'max:20'],
            'no_hp' => [
                'required',
                'string',
                'max:20',
                'regex:/^(0|62|\+62)8[1-9][0-9]{6,11}$/',
            ],
            'asal_sekolah' => ['required', 'string', 'max:100'],
            'tempat_lahir' => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['nullable', 'date'],
            'alamat' => ['nullable', 'string'],
            'tahun_lulus' => ['nullable', 'numeric', 'max:4'],
            'nama_ayah' => ['nullable', 'string', 'max:100'],
            'nama_ibu' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages()
    {
        return [
            'nama_lengkap.required' => 'Nama lengkap harus diisi.',
            'nama_lengkap.string' => 'Nama lengkap harus berupa string/karakter.',
            'nama_lengkap.max' => 'Nama lengkap tidak boleh lebih dari 100 karakter.',

            'nisn.required' => 'NISN harus diisi.',
            'nisn.string' => 'NISN harus berupa string/karakter.',
            'nisn.max' => 'NISN tidak boleh lebih dari 20 karakter.',

            'nik.required' => 'NIK harus diisi.',
            'nik.string' => 'NIK harus berupa string/karakter.',
            'nik.max' => 'NIK tidak boleh lebih dari 20 karakter.',

            'jenis_kelamin.required' => 'Jenis kelamin harus diisi.',
            'jenis_kelamin.in' => 'Jenis kelamin harus berupa Laki-laki atau Perempuan.',

            'jurusan.required' => 'Jurusan harus diisi.',
            'jurusan.string' => 'Jurusan harus berupa string/karakter.',
            'jurusan.max' => 'Jurusan tidak boleh lebih dari 20 karakter.',

            'no_hp.required' => 'Nomor HP harus diisi.',
            'no_hp.string' => 'Nomor HP harus berupa string/karakter.',
            'no_hp.max' => 'Nomor HP tidak boleh lebih dari 20 karakter.',
            'no_hp.regex' => 'Nomor HP harus dalam format Indonesia (08xxxxxxxx atau +628xxxxxx).',

            'asal_sekolah.required' => 'Asal sekolah harus diisi.',
            'asal_sekolah.string' => 'Asal sekolah harus berupa string/karakter.',
            'asal_sekolah.max' => 'Asal sekolah tidak boleh lebih dari 100 karakter.',

            'tempat_lahir.string' => 'Tempat lahir harus berupa string/karakter.',
            'tempat_lahir.max' => 'Tempat lahir tidak boleh lebih dari 100 karakter.',

            'tanggal_lahir.date' => 'Tanggal lahir harus berupa tanggal.',

            'alamat.string' => 'Alamat harus berupa string/karakter.',

            'tahun_lulus.numeric' => 'Tahun lulus harus berupa angka.',

            'nama_ayah.required' => 'Nama ayah harus diisi.',
            'nama_ayah.string' => 'Nama ayah harus berupa string/karakter.',
            'nama_ayah.max' => 'Nama ayah tidak boleh lebih dari 100 karakter.',

            'nama_ibu.required' => 'Nama ibu harus diisi.',
            'nama_ibu.string' => 'Nama ibu harus berupa string/karakter.',
            'nama_ibu.max' => 'Nama ibu tidak boleh lebih dari 100 karakter.',
        ];
    }
}
