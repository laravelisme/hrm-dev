<?php

namespace App\Http\Requests\MasterData\SaldoCuti;

use Illuminate\Foundation\Http\FormRequest;

class SaldoCutiStoreFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        return $user && $user->hasRole('hr');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'jenis' => 'required|string|max:255',
            'jumlah'     => 'required|integer|min:0',
            'm_jabatan_id' => 'required|exists:m_jabatans,id',
        ];
    }
}
