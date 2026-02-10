<?php

namespace App\Http\Requests\Api\Presensi;

use Illuminate\Foundation\Http\FormRequest;

class PresensiStoreFormRequest extends FormRequest
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
            'check_in_time' => 'required|date_format:H:i',
            'check_in_latitude' => 'nullable|numeric',
            'check_in_longitude' => 'nullable|numeric',
            'check_in_img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'check_in_info' => 'nullable|string',
            'check_in_date' => 'required|date',
            'time_zone' => 'required|string',
        ];
    }
}
