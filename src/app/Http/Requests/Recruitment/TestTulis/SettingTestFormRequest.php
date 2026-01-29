<?php

namespace App\Http\Requests\Recruitment\TestTulis;

use Illuminate\Foundation\Http\FormRequest;

class SettingTestFormRequest extends FormRequest
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
            'result_psikologi' => 'required|string',
            'result_teknikal' => 'required|string',
            'nik' => 'required|string|exists:m_calon_karyawans,nik',
//            'token' => 'required|string',
        ];
    }
}
