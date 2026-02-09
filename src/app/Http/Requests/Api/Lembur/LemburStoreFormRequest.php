<?php

namespace App\Http\Requests\Api\Lembur;

use Illuminate\Foundation\Http\FormRequest;

class LemburStoreFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('api')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date' => 'required|date',
            'durasi_diajukan_menit' => 'required|integer|min:1',
            'note' => 'nullable|string',
        ];
    }
}
