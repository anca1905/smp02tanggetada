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
            'no_kk' => ['nullable', 'string', 'max:20'],
            'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
            'agama' => ['nullable', 'string', 'max:30'],
            'tempat_tinggal' => ['nullable', 'string', 'max:50'],
            'moda_transportasi' => ['nullable', 'string', 'max:50'],
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
            
            // Ortu & Wali
            'nama_ayah' => ['nullable', 'string', 'max:100'],
            'pekerjaan_ayah' => ['nullable', 'string', 'max:50'],
            'penghasilan_ayah' => ['nullable', 'string', 'max:50'],
            'nama_ibu' => ['nullable', 'string', 'max:100'],
            'pekerjaan_ibu' => ['nullable', 'string', 'max:50'],
            'penghasilan_ibu' => ['nullable', 'string', 'max:50'],
            'nama_wali' => ['nullable', 'string', 'max:100'],
            'pekerjaan_wali' => ['nullable', 'string', 'max:50'],
            'penghasilan_wali' => ['nullable', 'string', 'max:50'],

            // Dokumen Persyaratan
            'doc_pas_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'doc_ijazah' => ['nullable', 'mimes:pdf', 'max:2048'],
            'doc_transkrip' => ['nullable', 'mimes:pdf', 'max:2048'],
            'doc_tka' => ['nullable', 'mimes:pdf', 'max:2048'],
            'doc_akta' => ['nullable', 'mimes:pdf', 'max:2048'],
            'doc_kk' => ['nullable', 'mimes:pdf', 'max:2048'],
            'doc_ktp_ayah' => ['nullable', 'mimes:pdf', 'max:2048'],
            'doc_ktp_ibu' => ['nullable', 'mimes:pdf', 'max:2048'],
        ];
    }

    public function messages()
    {
        return [
            'nama_lengkap.required' => 'Nama lengkap harus diisi.',
            'nisn.required' => 'NISN harus diisi.',
            'nik.required' => 'NIK harus diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin harus diisi.',
            'no_hp.required' => 'Nomor WhatsApp/Telepon harus diisi.',
            'no_hp.regex' => 'Nomor HP harus dalam format Indonesia yang valid.',
            'asal_sekolah.required' => 'Asal sekolah harus diisi.',
            'doc_pas_photo.image' => 'Pas Photo harus berupa gambar.',
            'doc_pas_photo.mimes' => 'Pas Photo harus berformat JPG, JPEG, atau PNG.',
            'doc_pas_photo.max' => 'Ukuran Pas Photo maksimal 2MB.',
            'doc_ijazah.mimes' => 'Ijazah harus berformat PDF.',
            'doc_ijazah.max' => 'Ukuran Ijazah maksimal 2MB.',
            'doc_transkrip.mimes' => 'Transkrip Nilai harus berformat PDF.',
            'doc_transkrip.max' => 'Ukuran Transkrip Nilai maksimal 2MB.',
            'doc_tka.mimes' => 'Sertifikat TKA harus berformat PDF.',
            'doc_tka.max' => 'Ukuran Sertifikat TKA maksimal 2MB.',
            'doc_akta.mimes' => 'Akta Kelahiran harus berformat PDF.',
            'doc_akta.max' => 'Ukuran Akta Kelahiran maksimal 2MB.',
            'doc_kk.mimes' => 'Kartu Keluarga (KK) harus berformat PDF.',
            'doc_kk.max' => 'Ukuran Kartu Keluarga maksimal 2MB.',
            'doc_ktp_ayah.mimes' => 'KTP Ayah harus berformat PDF.',
            'doc_ktp_ayah.max' => 'Ukuran KTP Ayah maksimal 2MB.',
            'doc_ktp_ibu.mimes' => 'KTP Ibu harus berformat PDF.',
            'doc_ktp_ibu.max' => 'Ukuran KTP Ibu maksimal 2MB.',
        ];
    }
}
