<?php

namespace App\Http\Requests\Setting;

use App\Models\Operator;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Override;

class UpdateOperatorProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Mendapatkan ID operator dari route parameter 'id'
        $operatorId = $this->route('id');

        return [
            'name' => 'required|string|max:100',
            'username' => [
                'required',
                Rule::unique('operators', 'username')->ignore(
                    $operatorId,
                    'operator_id',
                ),
            ],
            'photo_url' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'current_password' => 'required_with:new_password,username',
            'new_password' => 'nullable|min:6|confirmed',
        ];
    }

    #[Override]
    public function messages(): array
    {
        return [
            'name.required' => 'Nama tidak boleh kosong.',
            'name.max' => 'Nama maksimal 100 karakter.',
            'username.required' => 'Username tidak boleh kosong.',
            'username.unique' => 'Username sudah digunakan, silakan pilih yang lain.',
            'photo_url.image' => 'File harus berupa gambar.',
            'photo_url.mimes' => 'Format gambar harus jpeg, png, atau jpg.',
            'photo_url.max' => 'Ukuran gambar maksimal 2MB.',
            'current_password.required_with' => 'Password lama wajib diisi jika ingin mengubah password atau username.',
            'new_password.min' => 'Password baru minimal 6 karakter.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $operatorId = $this->route('id');
            $operator = Operator::where('operator_id', $operatorId)->first();

            if ($this->filled('current_password') && $operator) {
                if (
                    ! Hash::check($this->current_password, $operator->password)
                ) {
                    $validator
                        ->errors()
                        ->add(
                            'current_password',
                            'Password lama tidak sesuai.',
                        );
                }
            }
        });
    }
}
