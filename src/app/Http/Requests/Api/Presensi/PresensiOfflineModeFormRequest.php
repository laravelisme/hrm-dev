<?php

namespace App\Http\Requests\Api\Presensi;

use Illuminate\Foundation\Http\FormRequest;

class PresensiOfflineModeFormRequest extends FormRequest
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
            'data' => 'required|array',
            'data.*.check_in_time' => 'required|date_format:H:i',
            'data.*.check_in_latitude' => 'nullable|numeric',
            'data.*.check_in_longitude' => 'nullable|numeric',
            'data.*.check_in_img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'data.*.check_in_info' => 'nullable|string',
            'data.*.check_in_date' => 'required|date',
            'data.*.check_out_date' => 'required|date',
            'data.*.check_out_time' => 'nullable|date_format:H:i',
            'data.*.check_out_latitude' => 'nullable|numeric',
            'data.*.check_out_longitude' => 'nullable|numeric',
            'data.*.check_out_img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'data.*.check_out_info' => 'nullable|string',
            'data.*.check_in_timezone' => 'required|string',
            'data.*.check_out_timezone' => 'required|string',
            'data.*.is_active' => 'required|boolean',
        ];
    }
}
