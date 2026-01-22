<?php

namespace App\Http\Requests\MasterData\Jabatan;

use Illuminate\Foundation\Http\FormRequest;

class JabatanStoreFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'kode' => 'required|string|max:255',
            'level' => 'required|integer',
        ];
    }
}
