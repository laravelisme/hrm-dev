<?php

namespace App\Http\Requests\CalonKaryawan\Interview;

use Illuminate\Foundation\Http\FormRequest;

class InterviewStoreFormRequest extends FormRequest
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
            'interview_date_hr' => 'required|date',
            'interview_time_hr' => 'required',
            'interview_hr_location' => 'required|string',
            'interview_date_user' => 'nullable|date',
            'interview_time_user' => 'nullable',
            'interview_user_location' => 'nullable|string',
            'interview_hr_notes' => 'nullable|string',
            'interview_user_notes' => 'nullable|string',
        ];
    }
}
