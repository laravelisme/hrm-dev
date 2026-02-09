<?php

namespace App\Http\Requests\Api\Lembur;

use Illuminate\Foundation\Http\FormRequest;

class LemburApproveFormRequest extends FormRequest
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
            'status_approval' => 'required|in:APPROVED,REJECTED',
            'note_approval' => 'nullable|string',
            'durasi_disetujui_menit' => 'required_if:status_approval,APPROVED|integer|min:1',
        ];
    }
}
