<?php

namespace App\Http\Requests\Transaksi\SaldoCutiTahunan;

use Illuminate\Foundation\Http\FormRequest;

class SaldoCutiTahunanUpdateFormRequest extends FormRequest
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
            'saldo' => 'required|integer|min:0',
            'sisa_saldo' => 'required|integer|min:0',
        ];
    }
}
