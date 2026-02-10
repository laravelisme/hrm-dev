<?php

namespace App\Http\Requests\Api\Presensi;

use Illuminate\Foundation\Http\FormRequest;

class PresensiCheckoutFormRequest extends FormRequest
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
            'check_out_time' => 'required|date_format:H:i',
            'check_out_latitude' => 'nullable|numeric',
            'check_out_longitude' => 'nullable|numeric',
            'check_out_img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'check_out_info' => 'nullable|string',
        ];
    }
}
