<?php

namespace App\Http\Requests\Subject;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class UpdateSubjectRequest extends FormRequest
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
        $subject = $this->route('subject');
        $id = is_object($subject) ? $subject->id : $subject;

        return [
            'code' => ['sometimes', 'string', 'unique:subjects,code'.$id],
            'name' => ['sometimes', 'string'],
        ];
    }

    /**
     * Get the validation error messages for the request.
     *
     * @return array<string, string>
     */
    #[Override]
    public function messages(): array
    {
        return [
            'code.string' => 'Kode mata pelajaran harus berupa string/karakter.',
            'code.unique' => 'Kode mata pelajaran sudah terdaftar.',

            'name.string' => 'Nama mata pelajaran harus berupa string/karakter.',
        ];
    }
}
